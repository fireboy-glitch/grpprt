
const themeToggle = document.getElementById('themeToggle');
const body = document.body;

const savedTheme = localStorage.getItem('theme') || 'light';
body.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

themeToggle.addEventListener('click', () => {
    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
});

function updateThemeIcon(theme) {
    const icon = themeToggle.querySelector('i');
    icon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
}

const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

document.querySelectorAll('.dropdown > .nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        if (window.innerWidth <= 992) {
            e.preventDefault();
            const dropdown = link.parentElement;
            dropdown.classList.toggle('active');
        }
    });
});

const heroSlider = document.getElementById('heroSlider');
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const prevBtn = document.getElementById('prevSlide');
const nextBtn = document.getElementById('nextSlide');

let currentSlide = 0;
let slideInterval;

function showSlide(n) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    currentSlide = (n + slides.length) % slides.length;
    
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function prevSlide() {
    showSlide(currentSlide - 1);
}

nextBtn.addEventListener('click', nextSlide);
prevBtn.addEventListener('click', prevSlide);

function startSlider() {
    slideInterval = setInterval(nextSlide, 5000);
}

function stopSlider() {
    clearInterval(slideInterval);
}

heroSlider.addEventListener('mouseenter', stopSlider);
heroSlider.addEventListener('mouseleave', startSlider);

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        showSlide(index);
    });
});

const testimonials = document.querySelectorAll('.testimonial');
const prevTestimonialBtn = document.getElementById('prevTestimonial');
const nextTestimonialBtn = document.getElementById('nextTestimonial');

let currentTestimonial = 0;

function showTestimonial(n) {
    testimonials.forEach(testimonial => testimonial.classList.remove('active'));
    
    currentTestimonial = (n + testimonials.length) % testimonials.length;
    testimonials[currentTestimonial].classList.add('active');
}

prevTestimonialBtn.addEventListener('click', () => {
    showTestimonial(currentTestimonial - 1);
});

nextTestimonialBtn.addEventListener('click', () => {
    showTestimonial(currentTestimonial + 1);
});

const trekData = [
    {
        id: 1,
        title: "Everest Base Camp Trek",
        location: "Sagarmatha Zone, Nepal",
        image: "images/trek-ebc.jpg",
        duration: "14 days",
        difficulty: "Challenging",
        altitude: "5,364m",
        price: 1450,
        region: "everest",
        season: "spring,autumn",
        description: "The classic trek to the base of the world's highest mountain, passing through Sherpa villages and breathtaking Himalayan scenery."
    },
    {
        id: 2,
        title: "Annapurna Base Camp Trek",
        location: "Annapurna Region, Nepal",
        image: "images/trek-abc.jpg",
        duration: "12 days",
        difficulty: "Moderate",
        altitude: "4,130m",
        price: 1100,
        region: "annapurna",
        season: "spring,autumn",
        description: "A spectacular journey to the base of Annapurna I, surrounded by towering peaks and diverse landscapes."
    },
    {
        id: 3,
        title: "Langtang Valley Trek",
        location: "Langtang Region, Nepal",
        image: "images/trek-langtang.jpg",
        duration: "10 days",
        difficulty: "Moderate",
        altitude: "4,984m",
        price: 850,
        region: "langtang",
        season: "spring,autumn",
        description: "Explore the beautiful Langtang Valley, known for its Tibetan culture, glaciers, and stunning mountain views."
    },
    {
        id: 4,
        title: "Mardi Himal Trek",
        location: "Annapurna Region, Nepal",
        image: "images/trek-mardi.jpg",
        duration: "8 days",
        difficulty: "Moderate",
        altitude: "4,500m",
        price: 750,
        region: "annapurna",
        season: "spring,autumn,winter",
        description: "A relatively new trek offering incredible close-up views of the Annapurna range and Machhapuchhre (Fishtail)."
    },
    {
        id: 5,
        title: "Upper Mustang Trek",
        location: "Mustang Region, Nepal",
        image: "images/trek-upper-mustang.jpg",
        duration: "16 days",
        difficulty: "Moderate",
        altitude: "3,840m",
        price: 2200,
        region: "mustang",
        season: "spring,autumn",
        description: "A journey to the forbidden kingdom of Mustang, with its unique Tibetan culture and arid landscapes."
    },
    {
        id: 6,
        title: "Manaslu Circuit Trek",
        location: "Manaslu Region, Nepal",
        image: "images/trek-manaslu.jpg",
        duration: "18 days",
        difficulty: "Strenuous",
        altitude: "5,106m",
        price: 1650,
        region: "manaslu",
        season: "spring,autumn",
        description: "A challenging trek around the eighth highest mountain in the world, offering remote trails and cultural diversity."
    },
    {
        id: 7,
        title: "Kanchenjunga Base Camp Trek",
        location: "Kanchenjunga Region, Nepal",
        image: "images/trek-kanchenjunga.jpg",
        duration: "24 days",
        difficulty: "Strenuous",
        altitude: "5,143m",
        price: 2800,
        region: "kanchenjunga",
        season: "spring,autumn",
        description: "A remote and challenging trek to the base of the world's third highest mountain in eastern Nepal."
    },
    {
        id: 8,
        title: "Ghorepani Poon Hill Trek",
        location: "Annapurna Region, Nepal",
        image: "images/trek-poonhill.jpg",
        duration: "7 days",
        difficulty: "Easy",
        altitude: "3,210m",
        price: 550,
        region: "annapurna",
        season: "spring,autumn,winter,summer",
        description: "A classic short trek with spectacular sunrise views over the Annapurna and Dhaulagiri mountain ranges."
    }
];

function renderTrekCards(treks) {
    const treksGrid = document.getElementById('treksGrid');
    treksGrid.innerHTML = '';
    
    treks.forEach(trek => {
        const trekCard = document.createElement('div');
        trekCard.className = 'trek-card';
        trekCard.innerHTML = `
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
                        <span>${getSeasonNames(trek.season)}</span>
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
        treksGrid.appendChild(trekCard);
    });
}

function getSeasonNames(seasonCodes) {
    const seasonMap = {
        'spring': 'Spring',
        'autumn': 'Autumn',
        'winter': 'Winter',
        'summer': 'Summer'
    };
    
    return seasonCodes.split(',').map(code => seasonMap[code]).join(', ');
}

function filterTreks() {
    const regionFilter = document.getElementById('regionFilter').value;
    const difficultyFilter = document.getElementById('difficultyFilter').value;
    const durationFilter = document.getElementById('durationFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    const seasonFilter = document.getElementById('seasonFilter').value;
    const searchTerm = document.getElementById('trekSearch').value.toLowerCase();
    
    let filteredTreks = trekData.filter(trek => {
        if (regionFilter !== 'all' && trek.region !== regionFilter) {
            return false;
        }
        
        if (difficultyFilter !== 'all' && trek.difficulty.toLowerCase() !== difficultyFilter) {
            return false;
        }
        
        if (durationFilter !== 'all') {
            const durationRange = durationFilter.split('-');
            const minDays = parseInt(durationRange[0]);
            const maxDays = durationRange[1] === '+' ? Infinity : parseInt(durationRange[1]);
            const trekDays = parseInt(trek.duration);
            
            if (trekDays < minDays || trekDays > maxDays) {
                return false;
            }
        }
        

        if (priceFilter !== 'all') {
            const maxPrice = priceFilter === '2500+' ? Infinity : parseInt(priceFilter);
            if (trek.price > maxPrice) {
                return false;
            }
        }
        
        if (seasonFilter !== 'all' && !trek.season.includes(seasonFilter)) {
            return false;
        }
        
        if (searchTerm && !trek.title.toLowerCase().includes(searchTerm) && 
            !trek.location.toLowerCase().includes(searchTerm) && 
            !trek.description.toLowerCase().includes(searchTerm)) {
            return false;
        }
        
        return true;
    });
    
    renderTrekCards(filteredTreks);
}

document.getElementById('applyFilters').addEventListener('click', filterTreks);
document.getElementById('resetFilters').addEventListener('click', resetFilters);

function resetFilters() {
    document.getElementById('regionFilter').value = 'all';
    document.getElementById('difficultyFilter').value = 'all';
    document.getElementById('durationFilter').value = 'all';
    document.getElementById('priceFilter').value = 'all';
    document.getElementById('seasonFilter').value = 'all';
    document.getElementById('trekSearch').value = '';
    
    renderTrekCards(trekData);
}

document.getElementById('trekSearch').addEventListener('input', filterTreks);

document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    

    alert(`Thank you for subscribing with ${email}! You'll receive our latest trekking updates soon.`);
    this.reset();
});

document.addEventListener('DOMContentLoaded', () => {
    renderTrekCards(trekData);
    startSlider();
    

    window.addEventListener('scroll', () => {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        }
        
        if (window.innerWidth > 992) {
            [data-theme="dark"] .header {
                if (window.scrollY > 100) {
                    header.style.background = 'rgba(26, 26, 46, 0.98)';
                } else {
                    header.style.background = 'rgba(26, 26, 46, 0.95)';
                }
            }
        }
    });
});