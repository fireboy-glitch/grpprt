class PackageManager {
    constructor() {
        this.treks = [];
        this.loadPackages();
    }
    
    async loadPackages() {
        try {
            const response = await fetch('data/packages-data.json');
            const data = await response.json();
            this.treks = data.treks;
            this.renderAllPackages();
        } catch (error) {
            console.error('Error loading packages:', error);
        }
    }
    
    renderAllPackages() {
        const grid = document.getElementById('allPackagesGrid');
        if (!grid) return;
        
        grid.innerHTML = '';
        
        this.treks.forEach(trek => {
            const trekCard = this.createTrekCard(trek);
            grid.appendChild(trekCard);
        });
    }
    
    createTrekCard(trek) {
        const card = document.createElement('div');
        card.className = 'trek-card';
        card.innerHTML = `
            <div class="trek-image" style="background-image: url('${trek.image}')">
                <span class="trek-badge">${trek.difficulty}</span>
            </div>
            <div class="trek-content">
                <h3 class="trek-title">${trek.title}</h3>
                <div class="trek-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${trek.location}</span>
                </div>
                <div class="trek-details">
                    <div class="trek-detail">
                        <i class="fas fa-calendar-alt"></i>
                        <span>${trek.duration}</span>
                    </div>
                    <div class="trek-detail">
                        <i class="fas fa-mountain"></i>
                        <span>${trek.altitude}</span>
                    </div>
                    <div class="trek-detail">
                        <i class="fas fa-sun"></i>
                        <span>${this.getSeasonNames(trek.season)}</span>
                    </div>
                </div>
                <p>${trek.description}</p>
                <div class="trek-price">
                    <div class="price">
                        $${trek.price} <span>per person</span>
                    </div>
                    <a href="trek-details.html?id=${trek.id}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        `;
        return card;
    }
    
    getSeasonNames(seasonCodes) {
        const seasonMap = {
            'spring': 'Spring',
            'autumn': 'Autumn',
            'winter': 'Winter',
            'summer': 'Summer'
        };
        
        return seasonCodes.split(',').map(code => seasonMap[code]).join(', ');
    }
    
    addTrek(newTrek) {
        this.treks.push(newTrek);
        this.savePackages();
        this.renderAllPackages();
    }
    
    removeTrek(trekId) {
        this.treks = this.treks.filter(trek => trek.id !== trekId);
        this.savePackages();
        this.renderAllPackages();
    }
    
    async savePackages() {

        const data = { treks: this.treks };
        localStorage.setItem('nepalOdysseyPackages', JSON.stringify(data));
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('allPackagesGrid')) {
        new PackageManager();
    }
});