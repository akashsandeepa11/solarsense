// Navigation functionality
document.addEventListener("DOMContentLoaded", () => {
  const header = document.getElementById("header");
  const navToggle = document.getElementById("nav-toggle");
  const navMenu = document.getElementById("nav-menu");

  // Header scroll effect
  window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });

  // Mobile menu toggle
  navToggle.addEventListener("click", () => {
    navMenu.classList.toggle("show");
  });

  // Close mobile menu when clicking on links
  document.querySelectorAll(".nav__link").forEach((link) => {
    link.addEventListener("click", () => {
      navMenu.classList.remove("show");
    });
  });

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // Add staggered animation delay for multiple elements
        if (entry.target.classList.contains("step")) {
          const stepNumber = entry.target.dataset.step;
          setTimeout(() => {
            entry.target.classList.add("animate");
          }, (stepNumber - 1) * 200);
        } else {
          entry.target.classList.add("animate");
        }
      }
    });
  }, observerOptions);

  // Observe elements for animation
  document
    .querySelectorAll(".features__item, .step, .fade-in, .testimonial")
    .forEach((el) => {
      observer.observe(el);
    });

  initializeCounterAnimations();
  initializeParallaxEffects();
  initializeCursorEffects();
  initializeTypingEffect();

  // Load installers
  loadInstallers();

  // Search and filter functionality
  const searchInput = document.getElementById("installer-search");
  const districtFilter = document.getElementById("district-filter");
  const serviceFilter = document.getElementById("service-filter");

  if (searchInput) {
    searchInput.addEventListener("input", filterInstallers);
  }
  if (districtFilter) {
    districtFilter.addEventListener("change", filterInstallers);
  }
  if (serviceFilter) {
    serviceFilter.addEventListener("change", filterInstallers);
  }
});

function initializeCounterAnimations() {
  const counters = document.querySelectorAll(".stat__number");

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const counter = entry.target;
        const target = Number.parseInt(
          counter.textContent.replace(/[^\d]/g, "")
        );
        const prefix = counter.textContent.match(/[^\d]+/)?.[0] || "";
        const suffix = counter.textContent.match(/\d+(.+)/)?.[1] || "";

        animateCounter(counter, 0, target, 2000, prefix, suffix);
        counterObserver.unobserve(counter);
      }
    });
  });

  counters.forEach((counter) => counterObserver.observe(counter));
}

function animateCounter(
  element,
  start,
  end,
  duration,
  prefix = "",
  suffix = ""
) {
  const startTime = performance.now();

  function updateCounter(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);

    // Easing function for smooth animation
    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
    const current = Math.floor(start + (end - start) * easeOutQuart);

    element.textContent = prefix + current.toLocaleString() + suffix;

    if (progress < 1) {
      requestAnimationFrame(updateCounter);
    }
  }

  requestAnimationFrame(updateCounter);
}

function initializeParallaxEffects() {
  const floatingElements = document.querySelectorAll(".floating-element");

  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const rate = scrolled * -0.5;

    floatingElements.forEach((element, index) => {
      const speed = 0.2 + index * 0.1;
      element.style.transform = `translateY(${rate * speed}px) rotate(${
        scrolled * 0.1
      }deg)`;
    });
  });
}

function initializeCursorEffects() {
  const buttons = document.querySelectorAll(".btn");

  buttons.forEach((button) => {
    button.addEventListener("mouseenter", (e) => {
      e.target.style.transform = "translateY(-2px) scale(1.02)";
    });

    button.addEventListener("mouseleave", (e) => {
      e.target.style.transform = "translateY(0) scale(1)";
    });

    button.addEventListener("mousedown", (e) => {
      e.target.style.transform = "translateY(0) scale(0.98)";
    });

    button.addEventListener("mouseup", (e) => {
      e.target.style.transform = "translateY(-2px) scale(1.02)";
    });
  });
}

function initializeTypingEffect() {
  const titleElement = document.querySelector(".hero__title");
  if (!titleElement) return;

  const originalText = titleElement.innerHTML;
  const words = originalText.split(" ");

  // Only animate on first load
  if (!sessionStorage.getItem("titleAnimated")) {
    titleElement.innerHTML = "";

    words.forEach((word, index) => {
      setTimeout(() => {
        if (index > 0) titleElement.innerHTML += " ";
        titleElement.innerHTML += word;

        // Add cursor effect on last word
        if (index === words.length - 1) {
          titleElement.innerHTML += '<span class="cursor">|</span>';
          setTimeout(() => {
            const cursor = titleElement.querySelector(".cursor");
            if (cursor) cursor.remove();
          }, 1000);
        }
      }, index * 300);
    });

    sessionStorage.setItem("titleAnimated", "true");
  }
}

function createInstallerCard(installer) {
  const card = document.createElement("div");
  card.className = "installer-card";
  card.dataset.district = installer.district;
  card.dataset.services = installer.services.join(",");

  card.innerHTML = `
        <div class="installer-card__header">
            <img src="${installer.logo}" alt="${
    installer.name
  }" class="installer-card__logo">
            <div class="installer-card__badge">
                <i class="fas fa-certificate"></i>
                <span>Verified</span>
            </div>
        </div>
        <h3 class="installer-card__name">${installer.name}</h3>
        <p class="installer-card__bio">${installer.bio}</p>
        <div class="installer-card__rating">
            ${Array(5)
              .fill()
              .map(
                (_, i) =>
                  `<i class="fas fa-star${
                    i < Math.floor(installer.rating) ? "" : "-o"
                  }"></i>`
              )
              .join("")}
            <span class="rating-text">(${installer.rating})</span>
        </div>
        <div class="installer-card__services">
            ${installer.services
              .map((service) => `<span class="service-tag">${service}</span>`)
              .join("")}
        </div>
        <div class="installer-card__actions">
            <a href="installer-profile.php?id=${
              installer.id
            }" class="btn btn--primary">
                <i class="fas fa-eye"></i>
                View Profile
            </a>
            <button class="btn btn--outline quick-quote-btn" data-installer="${
              installer.name
            }">
                <i class="fas fa-calculator"></i>
                Quick Quote
            </button>
        </div>
    `;

  // Add hover animation
  card.addEventListener("mouseenter", () => {
    card.style.transform = "translateY(-8px) scale(1.02)";
  });

  card.addEventListener("mouseleave", () => {
    card.style.transform = "translateY(0) scale(1)";
  });

  // Add quick quote functionality
  const quickQuoteBtn = card.querySelector(".quick-quote-btn");
  quickQuoteBtn.addEventListener("click", (e) => {
    e.preventDefault();
    showQuickQuoteModal(installer.name);
  });

  return card;
}

function showQuickQuoteModal(installerName) {
  const modal = document.createElement("div");
  modal.className = "quick-quote-modal";
  modal.innerHTML = `
    <div class="modal-content">
      <div class="modal-header">
        <h3>Quick Quote Request</h3>
        <button class="modal-close">&times;</button>
      </div>
      <div class="modal-body">
        <p>Request a quick quote from <strong>${installerName}</strong></p>
        <form class="quick-quote-form">
          <input type="text" placeholder="Your Name" required>
          <input type="email" placeholder="Your Email" required>
          <input type="tel" placeholder="Phone Number" required>
          <select required>
            <option value="">System Size</option>
            <option value="1-3kw">1-3 kW</option>
            <option value="3-5kw">3-5 kW</option>
            <option value="5-10kw">5-10 kW</option>
            <option value="10kw+">10+ kW</option>
          </select>
          <button type="submit" class="btn btn--primary">
            <i class="fas fa-paper-plane"></i>
            Send Request
          </button>
        </form>
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  // Close modal functionality
  const closeBtn = modal.querySelector(".modal-close");
  closeBtn.addEventListener("click", () => {
    modal.remove();
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.remove();
    }
  });

  // Form submission
  const form = modal.querySelector(".quick-quote-form");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    // Simulate form submission
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    showLoading(submitBtn);

    setTimeout(() => {
      hideLoading(submitBtn, originalText);
      modal.innerHTML = `
        <div class="modal-content success">
          <div class="success-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <h3>Request Sent Successfully!</h3>
          <p>${installerName} will contact you within 24 hours.</p>
          <button class="btn btn--primary" onclick="this.closest('.quick-quote-modal').remove()">
            Close
          </button>
        </div>
      `;
    }, 2000);
  });
}

// Sample installer data
const installers = [
  {
    id: 1,
    name: "SolarTech Solutions",
    bio: "Leading solar installer with 10+ years experience in residential and commercial projects.",
    district: "colombo",
    services: ["residential", "commercial"],
    rating: 4.8,
    logo: "/solar-company-logo.png",
  },
  {
    id: 2,
    name: "Green Energy Lanka",
    bio: "Specialized in eco-friendly solar solutions with excellent customer service.",
    district: "kandy",
    services: ["residential", "maintenance"],
    rating: 4.9,
    logo: "/solar-company-logo.png",
  },
  {
    id: 3,
    name: "PowerSun Installations",
    bio: "Expert team providing comprehensive solar solutions across Sri Lanka.",
    district: "galle",
    services: ["commercial", "maintenance"],
    rating: 4.7,
    logo: "/solar-company-logo.png",
  },
  {
    id: 4,
    name: "Renewable Solutions",
    bio: "Innovative solar technology with smart monitoring systems.",
    district: "colombo",
    services: ["residential", "commercial", "maintenance"],
    rating: 4.6,
    logo: "/solar-company-logo.png",
  },
  {
    id: 5,
    name: "EcoSolar Lanka",
    bio: "Sustainable energy solutions with competitive pricing and quality service.",
    district: "jaffna",
    services: ["residential"],
    rating: 4.8,
    logo: "/solar-company-logo.png",
  },
  {
    id: 6,
    name: "SunPower Systems",
    bio: "Premium solar installations with 25-year warranty and monitoring.",
    district: "kandy",
    services: ["commercial", "maintenance"],
    rating: 4.9,
    logo: "/solar-company-logo.png",
  },
];

function loadInstallers() {
  const grid = document.getElementById("installer-grid");
  if (!grid) return;

  grid.innerHTML = "";

  installers.forEach((installer, index) => {
    const card = createInstallerCard(installer);
    setTimeout(() => {
      grid.appendChild(card);
      card.style.opacity = "0";
      card.style.transform = "translateY(30px)";

      requestAnimationFrame(() => {
        card.style.transition = "all 0.5s ease";
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
      });
    }, index * 100);
  });
}

function filterInstallers() {
  const searchTerm =
    document.getElementById("installer-search")?.value.toLowerCase() || "";
  const districtFilter =
    document.getElementById("district-filter")?.value || "";
  const serviceFilter = document.getElementById("service-filter")?.value || "";

  const cards = document.querySelectorAll(".installer-card");

  cards.forEach((card) => {
    const name = card
      .querySelector(".installer-card__name")
      .textContent.toLowerCase();
    const district = card.dataset.district;
    const services = card.dataset.services;

    const matchesSearch = name.includes(searchTerm);
    const matchesDistrict = !districtFilter || district === districtFilter;
    const matchesService = !serviceFilter || services.includes(serviceFilter);

    if (matchesSearch && matchesDistrict && matchesService) {
      card.style.display = "block";
      card.style.animation = "fadeIn 0.3s ease";
    } else {
      card.style.display = "none";
    }
  });
}

// Form validation helper
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return false;

  const inputs = form.querySelectorAll(
    "input[required], select[required], textarea[required]"
  );
  let isValid = true;

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      input.classList.add("error");
      isValid = false;
    } else {
      input.classList.remove("error");
    }
  });

  return isValid;
}

// Loading state helper
function showLoading(element) {
  element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
  element.disabled = true;
}

function hideLoading(element, originalText) {
  element.innerHTML = originalText;
  element.disabled = false;
}
