/**
 * Card Slider - Vanilla JS Implementation
 * Handles circular card rotation with drag/click navigation
 */

class CardSlider {
    constructor() {
        this.circle = document.querySelector('.card-slider-circle');
        this.cards = document.querySelectorAll('.card-slider-card');
        this.dragHandle = document.querySelector('.card-slider-drag-handle');
        this.prevBtn = document.querySelector('.nav-prev');
        this.nextBtn = document.querySelector('.nav-next');
        this.activeCardTitle = document.querySelector('.active-card-title');
        this.activeCardDesc = document.querySelector('.active-card-description');

        this.totalCards = this.cards.length;
        this.currentIndex = 0;
        this.isDragging = false;
        this.dragStartX = 0;
        this.dragStartRotation = 0;
        this.currentRotation = 0;
        this.lastValidRotation = 0;
        this.dragThreshold = 5;
        this.dragSensitivity = 0.5;
        this.animationDuration = 600; // ms
        this.cardRotationAngle = 360 / this.totalCards;

        this.cardData = [
            { title: '#1 for startups and unicorns', description: 'We\'re #1 in Europe for startups per capita and #1 globally for unicorns per capita' },
            { title: 'Digital first nation', description: 'Estonia was the first country to implement digital citizenship' },
            { title: 'Blockchain innovation', description: 'Leading the way in blockchain and distributed ledger technology' },
            { title: 'E-governance leader', description: 'We pioneered the world\'s first nationwide digital democracy' },
            { title: 'Tech talent hub', description: 'Home to the most innovative minds in European technology' },
            { title: 'Secure infrastructure', description: 'Protecting digital sovereignty with cutting-edge security' },
            { title: 'Innovation ecosystem', description: 'Creating world-class digital services for the future' },
            { title: 'Digital citizenship', description: 'Empowering people through digital rights and freedoms' },
            { title: 'Tech pioneer', description: 'Leading Europe in digital transformation' },
            { title: 'Smart solutions', description: 'Building intelligent systems for modern challenges' },
            { title: 'Digital excellence', description: 'Setting new standards in technology' },
            { title: 'Future-ready', description: 'Prepared for tomorrow\'s digital world' },
            { title: 'Connected nation', description: 'Linking communities through digital networks' },
            { title: 'Innovation leader', description: 'Driving technological advancement forward' },
            { title: 'Digital vision', description: 'Imagining and creating digital futures' },
            { title: 'Global influence', description: 'Shaping the world through digital progress' }
        ];

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateCardsDisplay();
    }

    setupEventListeners() {
        // Drag events
        if (this.dragHandle) {
            this.dragHandle.addEventListener('mousedown', (e) => this.startDrag(e));
            this.dragHandle.addEventListener('touchstart', (e) => this.startDrag(e));
        }
        
        document.addEventListener('mousemove', (e) => this.drag(e));
        document.addEventListener('touchmove', (e) => this.drag(e));
        document.addEventListener('mouseup', () => this.endDrag());
        document.addEventListener('touchend', () => this.endDrag());

        // Button clicks
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
        this.isDragging = true;
        this.dragStartX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        this.dragStartRotation = this.currentRotation;
    }

    drag(e) {
        if (!this.isDragging) return;

        const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        const dragDistance = clientX - this.dragStartX;

        if (Math.abs(dragDistance) > this.dragThreshold) {
            e.preventDefault?.();
            this.currentRotation = this.dragStartRotation + (dragDistance * this.dragSensitivity);
            this.updateCardsDisplay(false);
        }
    }

    endDrag() {
        if (!this.isDragging) return;
        this.isDragging = false;

        // Snap to nearest card
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
        this.cards.forEach((card, index) => {
            const cardRotation = (index * this.cardRotationAngle) - this.currentRotation;
            const normalizedRotation = ((cardRotation % 360) + 360) % 360;

            // Calculate distance from center (0-180 degrees)
            const distanceFromCenter = Math.abs(Math.min(normalizedRotation, 360 - normalizedRotation));
            
            // Calculate scale - center card is 1.2x, sides are smaller
            const scale = 1 + (1 - (distanceFromCenter / 90)) * 0.3;
            
            // Calculate opacity - all visible, center is opaque
            const opacity = Math.max(1 - (distanceFromCenter / 180) * 0.2, 0.6);

            card.style.transition = animate ? `transform ${this.animationDuration}ms cubic-bezier(0.34, 1.56, 0.64, 1), opacity ${this.animationDuration}ms ease-out` : 'none';
            card.style.transform = `rotate(${cardRotation}deg) scale(${scale})`;
            card.style.opacity = opacity;
        });

        // Update active card info
        const cardInfo = this.cardData[this.currentIndex];
        if (cardInfo) {
            this.activeCardTitle.textContent = cardInfo.title;
            this.activeCardDesc.textContent = cardInfo.description;
        }
    }
}

// Initialize slider when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new CardSlider();
    });
} else {
    new CardSlider();
}
