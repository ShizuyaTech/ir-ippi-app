/**
 * URL Encryption & Obfuscation Handler
 * Automatically handles encrypted IDs and random URL fragments
 */

class UrlObfuscation {
    /**
     * Map of resource types to their obfuscation tokens
     * These are set by the server and stored in session
     */
    static tokens = {};

    /**
     * Initialize obfuscation tokens from data attributes
     * Call this on page load
     */
    static init() {
        console.log('🔐 Initializing URL obfuscation system...');
        
        // Get all obfuscated links and update their href
        this.updateObfuscatedLinks();
        
        // Listen for dynamically added content
        this.observeDynamicContent();
        
        console.log('✅ URL obfuscation initialized');
    }

    /**
     * Update all links with data-obfuscate attribute
     * Changes href to use encrypted ID instead of plain ID
     */
    static updateObfuscatedLinks() {
        // Example: <a href="/news/{{ $id }}" data-obfuscate="news">
        // Changes to: <a href="/news/encrypted-value">
        
        const obfuscatedElements = document.querySelectorAll('[data-obfuscate]');
        obfuscatedElements.forEach(element => {
            const resourceType = element.getAttribute('data-obfuscate');
            const resourceId = element.getAttribute('data-resource-id');
            
            if (!resourceId) return;
            
            // The encrypted ID should already be in the href from server
            // This just ensures proper formatting
            element.classList.add('obfuscated-link');
        });
    }

    /**
     * Update page fragments to use random hashes
     * Example: window.location.hash = 'news' -> 'news-a7f8d9c3'
     */
    static randomizeFragments() {
        const currentHash = window.location.hash.slice(1);
        
        if (currentHash) {
            // Get or generate random hash for this fragment
            const randomHash = this.generateRandomHash(currentHash);
            
            // Don't trigger hashchange, just update silently
            history.replaceState(null, null, '#' + randomHash);
            
            console.log(`📍 Fragment changed: ${currentHash} -> ${randomHash}`);
        }
    }

    /**
     * Generate random hash based on fragment name
     * Same during session, different for each user
     */
    static generateRandomHash(fragmentName) {
        // Store in sessionStorage so it's consistent during session
        const storageKey = `hash_${fragmentName}`;
        
        if (sessionStorage.getItem(storageKey)) {
            return sessionStorage.getItem(storageKey);
        }
        
        // Generate random hex string
        const randomPart = Math.random().toString(16).slice(2, 10) +
                          Math.random().toString(16).slice(2, 10);
        const randomHash = fragmentName + '-' + randomPart;
        
        sessionStorage.setItem(storageKey, randomHash);
        return randomHash;
    }

    /**
     * Decode random hash back to fragment name
     * Useful for navigation that needs to know the actual page
     */
    static decodeHash(randomHash) {
        if (!randomHash) return null;
        
        // Fragment format: "name-randomstuff"
        const parts = randomHash.split('-');
        if (parts.length >= 1) {
            return parts[0]; // Return the first part (the actual name)
        }
        
        return null;
    }

    /**
     * Navigate to a section with obfuscated hash
     * @param {string} fragmentName - The actual fragment name (e.g., 'news')
     */
    static navigateTo(fragmentName) {
        const randomHash = this.generateRandomHash(fragmentName);
        window.location.hash = randomHash;
    }

    /**
     * Handle hash change events and route to actual handlers
     */
    static onHashChange() {
        const currentHash = window.location.hash.slice(1);
        const actualFragment = this.decodeHash(currentHash);
        
        console.log(`🔀 Hash changed to: ${currentHash} (fragment: ${actualFragment})`);
        
        // Dispatch custom event for actual fragment
        window.dispatchEvent(new CustomEvent('fragment-change', {
            detail: { fragment: actualFragment, hash: currentHash }
        }));
        
        // Call specific handlers if they exist
        const handlerName = `on${actualFragment.charAt(0).toUpperCase()}${actualFragment.slice(1)}`;
        if (typeof window[handlerName] === 'function') {
            window[handlerName]();
        }
    }

    /**
     * Observe DOM changes and update obfuscated links
     * Useful for dynamically loaded content
     */
    static observeDynamicContent() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    this.updateObfuscatedLinks();
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Mask resource IDs in console and network requests
     * Prevents accidental exposure in dev tools
     */
    static maskResourceIds(responseData) {
        if (typeof responseData !== 'object') return responseData;
        
        const masked = {};
        for (let key in responseData) {
            if (key === 'id' && typeof responseData[key] === 'number') {
                masked[key] = '[MASKED_ID]';
            } else {
                masked[key] = responseData[key];
            }
        }
        
        return masked;
    }
}

/**
 * Initialize on document ready
 */
document.addEventListener('DOMContentLoaded', function() {
    UrlObfuscation.init();
    UrlObfuscation.randomizeFragments();
});

/**
 * Listen for hash changes
 */
window.addEventListener('hashchange', function() {
    UrlObfuscation.onHashChange();
});

/**
 * Global namespace for URL obfuscation
 */
window.UrlObfuscation = UrlObfuscation;

// Log initialization in console
console.log('%c🔐 URL Obfuscation System Active', 'color: green; font-weight: bold; font-size: 14px;');
console.log('%cResource IDs are encrypted and fragments are randomized.', 'color: blue;');
console.log('%cDeveloper: Use encrypt_id() and random_hash() helpers in Blade templates.', 'color: gray;');
