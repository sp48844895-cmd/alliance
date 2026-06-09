(function () {
  const root = document.getElementById('investcg-card-slider');
  if (!root) return;

  class CardSlider {
    constructor(container) {
      this.root = container;
      this.circle = container.querySelector('.card-slider-circle');
      this.cards = container.querySelectorAll('.card-slider-card');
      this.dragSurface = container.querySelector('.card-slider-container');
      this.prevBtn = container.querySelector('.nav-prev');
      this.nextBtn = container.querySelector('.nav-next');
      this.activeCardTitle = container.querySelector('.active-card-title');
      this.activeCardDesc = container.querySelector('.active-card-description');

      this.totalCards = this.cards.length;
      if (!this.totalCards) return;

      this.currentIndex = 0;
      this.isDragging = false;
      this.dragStartX = 0;
      this.dragStartRotation = 0;
      this.currentRotation = 0;
      this.dragThreshold = 5;
      this.dragSensitivity = 0.5;
      this.animationDuration = 600;
      this.cardRotationAngle = 360 / this.totalCards;
      this.cardData = Array.from(this.cards).map((card) => ({
        title: card.dataset.title || '',
        description: card.dataset.description || '',
        url: card.dataset.url || '',
      }));

      this.mobileQuery = window.matchMedia('(max-width: 767px)');
      this.isMobile = this.mobileQuery.matches;

      this.init();
    }

    init() {
      this.applyLayoutMode();
      this.setupEventListeners();
      this.updateCardsDisplay(false);

      this.mobileQuery.addEventListener('change', () => {
        this.isMobile = this.mobileQuery.matches;
        this.applyLayoutMode();
        this.currentRotation = this.currentIndex * this.cardRotationAngle;
        this.updateCardsDisplay(false);
      });
    }

    applyLayoutMode() {
      if (!this.dragSurface) return;
      this.dragSurface.classList.toggle('is-mobile', this.isMobile);
      this.dragSensitivity = this.isMobile ? 0.35 : 0.5;
    }

    setupEventListeners() {
      if (this.dragSurface) {
        this.dragSurface.addEventListener('mousedown', (e) => this.startDrag(e));
        this.dragSurface.addEventListener('touchstart', (e) => this.startDrag(e), { passive: true });
      }

      this.onMouseMove = (e) => this.drag(e);
      this.onTouchMove = (e) => this.drag(e);
      this.onDragEnd = () => this.endDrag();

      document.addEventListener('mousemove', this.onMouseMove);
      document.addEventListener('touchmove', this.onTouchMove, { passive: false });
      document.addEventListener('mouseup', this.onDragEnd);
      document.addEventListener('touchend', this.onDragEnd);

      if (this.prevBtn) {
        this.prevBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          this.rotatePrev();
        });
      }

      if (this.nextBtn) {
        this.nextBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          this.rotateNext();
        });
      }
    }

    startDrag(e) {
      if (this.isMobile) return;
      if (e.target.closest('.nav-btn') || e.target.closest('.card-slider-link')) {
        return;
      }

      this.isDragging = true;
      this.dragStartX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
      this.dragStartRotation = this.currentRotation;
    }

    drag(e) {
      if (!this.isDragging || this.isMobile) return;

      const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
      const dragDistance = clientX - this.dragStartX;

      if (Math.abs(dragDistance) > this.dragThreshold) {
        e.preventDefault?.();
        this.currentRotation = this.dragStartRotation + dragDistance * this.dragSensitivity;
        this.updateCardsDisplay(false);
      }
    }

    endDrag() {
      if (!this.isDragging || this.isMobile) return;
      this.isDragging = false;

      const nearestIndex = Math.round(this.currentRotation / this.cardRotationAngle) % this.totalCards;
      this.snapToCard(nearestIndex);
    }

    snapToCard(index) {
      this.currentIndex = ((index % this.totalCards) + this.totalCards) % this.totalCards;
      this.currentRotation = this.currentIndex * this.cardRotationAngle;
      this.updateCardsDisplay(true);
    }

    rotateNext() {
      this.currentIndex = (this.currentIndex + 1) % this.totalCards;
      this.currentRotation = this.currentIndex * this.cardRotationAngle;
      this.updateCardsDisplay(true);
    }

    rotatePrev() {
      this.currentIndex = (this.currentIndex - 1 + this.totalCards) % this.totalCards;
      this.currentRotation = this.currentIndex * this.cardRotationAngle;
      this.updateCardsDisplay(true);
    }

    updateCardsDisplay(animate = true) {
      if (this.isMobile) {
        this.updateMobileDisplay(animate);
        return;
      }

      this.cards.forEach((card, index) => {
        const cardRotation = index * this.cardRotationAngle - this.currentRotation;
        const normalizedRotation = ((cardRotation % 360) + 360) % 360;
        const distanceFromCenter = Math.abs(Math.min(normalizedRotation, 360 - normalizedRotation));
        const scale = 1 + (1 - distanceFromCenter / 90) * 0.3;
        const opacity = Math.max(1 - (distanceFromCenter / 180) * 0.2, 0.6);

        card.style.transition = animate
          ? `transform ${this.animationDuration}ms cubic-bezier(0.34, 1.56, 0.64, 1), opacity ${this.animationDuration}ms ease-out`
          : 'none';
        card.style.transform = `rotate(${cardRotation}deg) scale(${scale})`;
        card.style.opacity = opacity;
        card.style.visibility = '';
        card.classList.toggle('is-active', index === this.currentIndex);
      });

      this.syncActiveCopy();
    }

    updateMobileDisplay(animate = true) {
      this.cards.forEach((card, index) => {
        const isActive = index === this.currentIndex;

        card.style.transition = animate
          ? `opacity ${this.animationDuration}ms ease-out, visibility ${this.animationDuration}ms ease-out`
          : 'none';
        card.style.transform = 'none';
        card.style.opacity = isActive ? '1' : '0';
        card.style.visibility = isActive ? 'visible' : 'hidden';
        card.classList.toggle('is-active', isActive);
      });

      this.syncActiveCopy();
    }

    syncActiveCopy() {
      const cardInfo = this.cardData[this.currentIndex];
      if (cardInfo && this.activeCardTitle && this.activeCardDesc) {
        this.activeCardTitle.textContent = cardInfo.title;
        this.activeCardDesc.textContent = cardInfo.description;
      }
    }
  }

  new CardSlider(root);
})();
