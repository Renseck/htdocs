class FlipCard extends HTMLElement {
    constructor() {
        super();
        this.alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~'
    }

    /* ========================================================================================== */
    connectedCallback() {
        const text = this.textContent.trim().toUpperCase();
        this.innerHTML = '';
        
        const container = this.createContainer("flip-card");

        const theme = this.getAttribute("theme") ?? "classic";
        container.classList.add(`theme-${theme}`);
        
        // Split text into individual characters
        for (let i = 0; i < text.length; i++) {
            const char = text[i];
            const charBox = this.createCharacterBox(char, i);
            container.appendChild(charBox);
        }
        
        this.appendChild(container);
    }

    /* ========================================================================================== */
    getStartCharacter() {
        const type = this.getAttribute("type");
        return type === "number" ? '0' : 'A';
    }

    /* ========================================================================================== */
    createContainer(container_name) {
        const container = document.createElement('div');
        container.className = `${container_name}-container`;
        return container;
    }

    /* ========================================================================================== */
    createCharacterBox(char, index) {
        const charBox = document.createElement('div');
        charBox.className = 'flip-card-char';
        
        if (char === ' ') {
            charBox.classList.add('space');
            charBox.innerHTML = '&nbsp;';
        } else {
            this.createFlipStructure(charBox, char, index);
        }
        
        return charBox;
    }

    /* ========================================================================================== */
    createFlipStructure(charBox, targetChar, index) {
        const elements = this.createFlipElements();
        
        charBox.appendChild(elements.topHalf);
        charBox.appendChild(elements.bottomHalf);
        charBox.appendChild(elements.flipTop);
        
        // Start animation with staggered delay
        setTimeout(() => {
            this.animateToTarget(charBox, targetChar);
        }, index * 100);
    }

    /* ========================================================================================== */
    createFlipElements() {
        return {
            topHalf: this.createHalfElement('flip-top'),
            bottomHalf: this.createHalfElement('flip-bottom'),
            flipTop: this.createHalfElement('flip-top-flip')
        };
    }

    /* ========================================================================================== */
    createHalfElement(className) {
        const element = document.createElement('div');
        element.className = className;
        
        const charSpan = document.createElement('span');
        charSpan.textContent = this.getStartCharacter();
        charSpan.className = 'char-display';
        
        element.appendChild(charSpan);
        return element;
    }

    /* ========================================================================================== */
    animateToTarget(charBox, targetChar) {
        const targetIndex = this.alphabet.indexOf(targetChar);
        const startChar = this.getStartCharacter();
        let currentIndex = this.alphabet.indexOf(startChar);
        
        const elements = this.getAnimationElements(charBox);
        
        const animate = () => {
            if (currentIndex >= targetIndex) return;
            
            const chars = this.getCharacterPair(currentIndex);
            const timing = this.calculateTiming(currentIndex, targetIndex);
            
            this.performFlipAnimation(elements, chars, timing, () => {
                currentIndex++;
                setTimeout(animate, timing.pauseBetweenFlips);
            });
        };
        
        animate();
    }

    /* ========================================================================================== */
    getAnimationElements(charBox) {
        return {
            topHalf: charBox.querySelector('.flip-top .char-display'),
            bottomHalf: charBox.querySelector('.flip-bottom .char-display'),
            flipTop: charBox.querySelector('.flip-top-flip'),
            flipChar: charBox.querySelector('.flip-top-flip .char-display')
        };
    }

    /* ========================================================================================== */
    getCharacterPair(currentIndex) {
        return {
            current: this.alphabet[currentIndex],
            next: this.alphabet[currentIndex + 1]
        };
    }

    /* ============================================================================================== */
    calculateTiming(currentIndex, targetIndex) {
        const remainingDistance = targetIndex - currentIndex;
        const totalDistance = targetIndex;

        if (remainingDistance > totalDistance * 0.7) {
            return {flipDuration: 50, pauseBetweenFlips: 5 };
        } else if (remainingDistance > totalDistance * 0.4) {
            return {flipDuration: 80, pauseBetweenFlips: 1 };
        } else if (remainingDistance > totalDistance * 0.2) {
            return {flipDuration: 120, pauseBetweenFlips: 20 };
        } else {
            return {flipDuration: 180, pauseBetweenFlips: 30 };
        }
    }

    /* ========================================================================================== */
    performFlipAnimation(elements, chars, timing, onComplete) {
        // Set initial state
        elements.flipChar.textContent = chars.current;
        elements.flipTop.style.transform = 'rotateX(0deg)';
        
        // Phase 1: Start flip
        setTimeout(() => {
            elements.topHalf.textContent = chars.next;
            elements.flipChar.textContent = chars.next;
            elements.flipTop.style.transform = 'rotateX(-90deg)';
            
            // Phase 2: Mid flip
            setTimeout(() => {
                elements.flipTop.style.transform = 'rotateX(-180deg)';
                
                // Phase 3: Complete flip
                setTimeout(() => {
                    this.resetFlipElement(elements.flipTop);
                    elements.bottomHalf.textContent = chars.next;
                    
                    onComplete();
                }, timing.flipDuration / 6);
            }, timing.flipDuration / 6);
        }, timing.flipDuration / 3);
    }

    /* ========================================================================================== */
    resetFlipElement(flipTop) {
        // Disable transition and hide element
        flipTop.style.transition = 'none';
        flipTop.style.visibility = 'hidden';
        flipTop.style.transform = 'rotateX(0deg)';
        
        // Re-enable transition and show element
        setTimeout(() => {
            flipTop.style.visibility = 'visible';
            flipTop.style.transition = 'transform 0.2s ease-in-out';
        }, 10);
    }
}


// Define the custom element
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        customElements.define('flip-card', FlipCard);
    });
} else {
    customElements.define('flip-card', FlipCard);
}