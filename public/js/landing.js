let animatedObserver = null;
const animatedSelectors =
  ".features__item, .step, .fade-in, .testimonial, .installer-card";

// Global function for appliance card toggle (used by onclick handlers)
function toggleAppliance(card, value) {
  const checkbox = card.querySelector('input[type="checkbox"]');
  if (!checkbox) return;

  // Toggle checkbox
  checkbox.checked = !checkbox.checked;

  // Update visual state
  const icon = card.querySelector("i");
  const textSpan = card.querySelector("span");

  if (checkbox.checked) {
    card.style.borderColor = "#fe9630";
    card.style.background = "rgba(254, 150, 48, 0.1)";
    if (icon) icon.style.color = "#fe9630";
    if (textSpan) textSpan.style.color = "#fe9630";
  } else {
    card.style.borderColor = "#e2e8f0";
    card.style.background = "#f8fafc";
    if (icon) icon.style.color = "#94a3b8";
    if (textSpan) textSpan.style.color = "#475569";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  initializeHeaderInteractions();
  initializeScrollAnimations();
  initializeCounterAnimations();
  initializeParallaxEffects();
  initializeButtonHoverEffects();
  initializeTypingEffect();
  initializeInstallerDirectory();
  initializeQuotationCalculator();
});

function initializeHeaderInteractions() {
  const header = document.getElementById("header");
  const navToggle = document.getElementById("nav-toggle");
  const navMenu = document.getElementById("nav-menu");

  if (header) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 100) {
        header.classList.add("scrolled");
      } else {
        header.classList.remove("scrolled");
      }
    });
  }

  navToggle?.addEventListener("click", () => {
    navMenu?.classList.toggle("show");
  });

  document.querySelectorAll(".nav__link").forEach((link) => {
    link.addEventListener("click", () => navMenu?.classList.remove("show"));
  });

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", (event) => {
      const targetId = anchor.getAttribute("href");
      if (!targetId || targetId === "#") return;
      const target = document.querySelector(targetId);
      if (!target) return;

      event.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });
}

function initializeScrollAnimations() {
  const animatedElements = document.querySelectorAll(animatedSelectors);
  if (!animatedElements.length) return;

  if (!("IntersectionObserver" in window)) {
    animatedElements.forEach((element) => element.classList.add("animate"));
    return;
  }

  animatedObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        if (entry.target.classList.contains("step")) {
          const stepNumber = Number(entry.target.dataset.step) || 0;
          setTimeout(
            () => entry.target.classList.add("animate"),
            Math.max(0, stepNumber - 1) * 200
          );
        } else {
          entry.target.classList.add("animate");
        }

        observer.unobserve(entry.target);
      });
    },
    {
      threshold: 0.15,
      rootMargin: "0px 0px -60px 0px",
    }
  );

  animatedElements.forEach((element) => animatedObserver.observe(element));
}

function observeAnimatedElement(element) {
  if (!element) return;

  if (!animatedObserver) {
    element.classList.add("animate");
    return;
  }

  animatedObserver.observe(element);
}

function initializeCounterAnimations() {
  const counters = document.querySelectorAll(".stat__number");
  if (!counters.length) return;

  if (!("IntersectionObserver" in window)) {
    counters.forEach((counter) => {
      const target = Number.parseInt(counter.textContent.replace(/[^\d]/g, ""));
      const prefix = counter.textContent.match(/^[^\d]+/)?.[0] || "";
      const suffix = counter.textContent.match(/\d+([^\d]+)$/)?.[1] || "";
      counter.textContent = `${prefix}${target.toLocaleString()}${suffix}`;
    });
    return;
  }

  const counterObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        const counter = entry.target;
        const target = Number.parseInt(
          counter.textContent.replace(/[^\d]/g, "")
        );
        const prefix = counter.textContent.match(/^[^\d]+/)?.[0] || "";
        const suffix = counter.textContent.match(/\d+([^\d]+)$/)?.[1] || "";

        animateCounter(counter, 0, target, 2000, prefix, suffix);
        counterObserver.unobserve(counter);
      });
    },
    { threshold: 0.6 }
  );

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

  function update(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 4);
    const value = Math.floor(start + (end - start) * eased);
    element.textContent = `${prefix}${value.toLocaleString()}${suffix}`;

    if (progress < 1) {
      requestAnimationFrame(update);
    }
  }

  requestAnimationFrame(update);
}

function initializeParallaxEffects() {
  const floatingElements = document.querySelectorAll(".floating-element");
  if (!floatingElements.length) return;

  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    floatingElements.forEach((element, index) => {
      const speed = 0.2 + index * 0.1;
      element.style.transform = `translateY(${scrolled * -0.15 * speed}px)`;
    });
  });
}

function initializeButtonHoverEffects() {
  document.querySelectorAll(".btn").forEach((button) => {
    button.addEventListener("mouseenter", () => {
      button.style.transform = "translateY(-2px) scale(1.02)";
    });

    button.addEventListener("mouseleave", () => {
      button.style.transform = "translateY(0) scale(1)";
    });

    button.addEventListener("mousedown", () => {
      button.style.transform = "translateY(0) scale(0.98)";
    });

    button.addEventListener("mouseup", () => {
      button.style.transform = "translateY(-2px) scale(1.02)";
    });
  });
}

function initializeTypingEffect() {
  const titleElement = document.querySelector(".hero__title");
  if (!titleElement || sessionStorage.getItem("titleAnimated")) return;

  const words = titleElement.innerHTML.split(" ");
  titleElement.innerHTML = "";

  words.forEach((word, index) => {
    setTimeout(() => {
      if (index > 0) titleElement.innerHTML += " ";
      titleElement.innerHTML += word;

      if (index === words.length - 1) {
        titleElement.innerHTML += '<span class="cursor">|</span>';
        setTimeout(() => {
          titleElement.querySelector(".cursor")?.remove();
        }, 900);
      }
    }, index * 260);
  });

  sessionStorage.setItem("titleAnimated", "true");
}

/* -------------------------------------------------------------------------- */
/*  Quotation Calculator Section                                              */
/* -------------------------------------------------------------------------- */
/* ============================================
   SOLAR QUOTATION CONFIGURATION
   ============================================
   Edit values below to update pricing and calculations.
   All prices are in Sri Lankan Rupees (LKR).
*/

const SOLAR_CONFIG = {

  // ─────────────────────────────────────────────
  // SYSTEM SIZING: Maps monthly bill to system capacity
  // ─────────────────────────────────────────────
  systemSizing: {
    // Monthly bill range -> recommended system size (kW)
    billToCapacity: {
      low: 3,          // Bill < Rs 15,000
      medium: 5,       // Bill Rs 15,000 - 30,000
      high: 7,         // Bill Rs 30,000 - 45,000
      "very-high": 10, // Bill > Rs 45,000
    },
  },

  // ─────────────────────────────────────────────
  // BASE COSTS: Per kW installation costs
  // ─────────────────────────────────────────────
  baseCosts: {
    // Price multiplier based on system size (larger = slightly cheaper per kW)
    capacityMultiplier: {
      3: 1.0,    // 3kW - base price
      5: 1.0,    // 5kW - same as base (was 1.12, lowered for realistic pricing)
      7: 0.95,   // 7kW - 5% discount per kW
      10: 0.90,  // 10kW - 10% discount per kW
    },
  },

  // ─────────────────────────────────────────────
  // PANEL COSTS: Price multiplier by panel type
  // ─────────────────────────────────────────────
  panels: {
    // Panel type -> price multiplier
    mono: {
      multiplier: 1.10,      // Monocrystalline - premium, high efficiency
      efficiency: 0.20,      // 20% efficiency
      label: "Monocrystalline",
    },
    poly: {
      multiplier: 1.0,       // Polycrystalline - standard, good value
      efficiency: 0.17,      // 17% efficiency  
      label: "Polycrystalline",
    },
  },

  // ─────────────────────────────────────────────
  // INVERTER COSTS: Price multiplier by inverter type
  // ─────────────────────────────────────────────
  inverters: {
    string: {
      multiplier: 1.0,       // String inverter - basic, no battery support
      label: "String Inverter",
    },
    hybrid: {
      multiplier: 1.25,      // Hybrid inverter - supports battery
      label: "Hybrid Inverter",
    },
  },

  // ─────────────────────────────────────────────
  // BATTERY COSTS: Fixed price by capacity
  // ─────────────────────────────────────────────
  batteries: {
    none: { price: 0, capacity: 0, label: "No Battery" },
    5: { price: 180000, capacity: 5, label: "5 kWh Battery" },      // ~Rs 36,000/kWh
    10: { price: 320000, capacity: 10, label: "10 kWh Battery" },   // ~Rs 32,000/kWh
  },

  // ─────────────────────────────────────────────
  // INSTALLATION COSTS: Based on roof type
  // ─────────────────────────────────────────────
  installation: {
    roofType: {
      tile: { price: 25000, label: "Tile Roof" },
      metal: { price: 18000, label: "Metal Roof" },
      flat: { price: 30000, label: "Flat Concrete" },
    },
  },

  // ─────────────────────────────────────────────
  // ADDITIONAL COSTS
  // ─────────────────────────────────────────────
  extras: {
    monitoring: {
      basic: { price: 12000, label: "Basic Monitoring" },
      advanced: { price: 35000, label: "Smart Monitoring + App" },
    },
    warranty: {
      10: { price: 0, years: 10, label: "10 Year Warranty" },
      15: { price: 25000, years: 15, label: "15 Year Warranty" },
      25: { price: 55000, years: 25, label: "25 Year Warranty" },
    },
  },

  // ─────────────────────────────────────────────
  // TAX & FEES
  // ─────────────────────────────────────────────
  fees: {
    taxRate: 0.08,  // 8% VAT and levies
  },

  // ─────────────────────────────────────────────
  // SAVINGS CALCULATION PARAMETERS
  // ─────────────────────────────────────────────
  savings: {
    // Average peak sun hours per day in Sri Lanka
    peakSunHours: 4.5,

    // System performance ratio (accounts for losses)
    performanceRatio: 0.80,  // 80% of theoretical output

    // CEB electricity tariff (Rs per kWh) - use average rate
    electricityTariff: 32,

    // Annual degradation rate of solar panels
    annualDegradation: 0.005,  // 0.5% per year

    // Usage pattern multipliers - affects how much solar generation is self-consumed
    // Day: Best direct solar utilization
    // Balanced: Average utilization (baseline)
    // Night: More grid dependency, less direct solar use
    usageMultiplier: {
      day: 0.9,
      balanced: 1.0,
      night: 0.8,
    },
  },

  // ─────────────────────────────────────────────
  // BACKUP NEEDS MAPPING
  // ─────────────────────────────────────────────
  backupMapping: {
    none: { battery: "none", inverter: "string" },
    essentials: { battery: "5", inverter: "hybrid" },
    full: { battery: "10", inverter: "hybrid" },
  },

  // ─────────────────────────────────────────────
  // PANEL PREFERENCE MAPPING
  // ─────────────────────────────────────────────
  preferenceMapping: {
    value: "poly",        // Budget-friendly option
    performance: "mono",  // Premium option
  },
};

// ─────────────────────────────────────────────
// INSTALLER DATA
// ─────────────────────────────────────────────
const installersData = [
  {
    id: 1,
    name: "SunPower Solutions",
    rating: 4.8,
    reviews: 247,
    baseRate: 85000,   // Rs per kW base installation rate
    markup: 1.0,       // Price multiplier (1.0 = no markup)
    experience: "12 yrs",
    region: "Island-wide",
  },
  {
    id: 2,
    name: "GreenEnergy Pro",
    rating: 4.7,
    reviews: 189,
    baseRate: 78000,
    markup: 1.0,
    experience: "9 yrs",
    region: "Western & Southern",
  },
  {
    id: 3,
    name: "EcoSolar Tech",
    rating: 4.9,
    reviews: 312,
    baseRate: 91000,
    markup: 1.0,
    experience: "14 yrs",
    region: "Island-wide",
  },
  {
    id: 4,
    name: "PowerSun Installation",
    rating: 4.6,
    reviews: 156,
    baseRate: 76000,
    markup: 1.0,
    experience: "8 yrs",
    region: "Central & Uva",
  },
];

// ─────────────────────────────────────────────
// QUOTATION STATE
// ─────────────────────────────────────────────
let currentStep = 1;
let selectedInstaller = null;

const quotationState = {
  installer: null,
  capacity: 5,
  panelType: "mono",
  inverterType: "string",
  battery: "none",
  roofType: "tile",
  monitoring: "basic",
  warranty: "10",
  usagePattern: "balanced",
  heavyLoads: [],
};

function initializeQuotationCalculator() {
  const quotationSection = document.getElementById("quotation-section");
  if (!quotationSection) return;

  loadInstallerOptions();
  setupQuotationEventListeners();
  goToStep(1);
}

function setupQuotationEventListeners() {
  const nextBtn = document.getElementById("next-step-btn");
  const prevBtn = document.getElementById("prev-step-btn");
  const startOverBtn = document.getElementById("start-over-btn");
  const quotationFooter = document.getElementById("quotation-footer");

  if (nextBtn) {
    nextBtn.addEventListener("click", handleNextStep);
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", handlePrevStep);
  }

  if (startOverBtn) {
    startOverBtn.addEventListener("click", () => {
      resetCalculator();
      if (quotationFooter) {
        quotationFooter.classList.remove("hidden");
      }
      goToStep(1);
      scrollToQuotation();
    });
  }

  const specControls = document.querySelectorAll(
    "#bill-amount, #usage-pattern, #backup-needs, #preference, #roof-type, #smart-features"
  );
  specControls.forEach((control) => {
    control.addEventListener("change", () => {
      updateStateFromInputs();
      if (currentStep >= 4) {
        renderConfirmation();
        displayPriceSummary();
      }
    });
  });

  document.querySelectorAll(".quote-trigger").forEach((trigger) => {
    trigger.addEventListener("click", (event) => {
      event.preventDefault();
      scrollToQuotation();
    });
  });

  // Appliance checkbox card interactions - click on card to toggle
  document.querySelectorAll(".checkbox-card").forEach((card) => {
    card.addEventListener("click", function (e) {
      e.preventDefault();
      const checkbox = this.querySelector('input[type="checkbox"]');
      if (!checkbox) return;

      // Toggle the checkbox
      checkbox.checked = !checkbox.checked;

      // Update visual state
      const content = this.querySelector(".checkbox-card__content");
      if (checkbox.checked) {
        content.style.borderColor = "#fe9630";
        content.style.background = "rgba(254, 150, 48, 0.1)";
        content.style.color = "#fe9630";
        const icon = content.querySelector("i");
        if (icon) icon.style.color = "#fe9630";
      } else {
        content.style.borderColor = "#e2e8f0";
        content.style.background = "#f8fafc";
        content.style.color = "#475569";
        const icon = content.querySelector("i");
        if (icon) icon.style.color = "#94a3b8";
      }
      updateStateFromInputs();
    });
  });
}

function initializeInstallerDirectory() {
  const grid = document.getElementById("installer-grid");
  if (!grid) return;

  const searchInput = document.getElementById("installer-search");
  const districtFilter = document.getElementById("district-filter");
  const serviceFilter = document.getElementById("service-filter");

  const directoryInstallers = [
    {
      name: "SunPower Solutions",
      district: "colombo",
      services: ["residential", "maintenance"],
      rating: 4.8,
      installs: 420,
      years: 12,
    },
    {
      name: "GreenEnergy Pro",
      district: "kandy",
      services: ["residential", "commercial"],
      rating: 4.7,
      installs: 360,
      years: 9,
    },
    {
      name: "EcoSolar Tech",
      district: "galle",
      services: ["commercial", "maintenance"],
      rating: 4.9,
      installs: 505,
      years: 14,
    },
    {
      name: "PowerSun Installation",
      district: "colombo",
      services: ["residential", "commercial"],
      rating: 4.6,
      installs: 295,
      years: 8,
    },
    {
      name: "Renew Lanka Solar",
      district: "jaffna",
      services: ["residential"],
      rating: 4.5,
      installs: 180,
      years: 6,
    },
  ];

  const renderInstallers = (data) => {
    grid.innerHTML = "";

    if (!data.length) {
      grid.innerHTML = `<p class="no-results">No installers match your search yet. Try adjusting your filters.</p>`;
      return;
    }

    data.forEach((installer) => {
      const card = document.createElement("div");
      card.className = "installer-card";
      card.innerHTML = `
        <div class="installer-card__header">
          <div>
            <div class="installer-card__name">${installer.name}</div>
            <div class="installer-card__rating">
              <i class="fas fa-star"></i>
              <span>${installer.rating.toFixed(1)} · ${installer.installs
        } installs</span>
            </div>
          </div>
          <span class="installer-card__badge">
            <i class="fas fa-check-circle"></i>
            Verified
          </span>
        </div>
        <p class="installer-card__bio">Serving ${installer.district.charAt(0).toUpperCase() +
        installer.district.slice(1)
        } district · ${installer.years}+ years experience</p>
        <div class="installer-card__services">
          ${installer.services
          .map(
            (service) =>
              `<span class="service-tag">${formatLabel(service)}</span>`
          )
          .join("")}
        </div>
        <div class="installer-card__actions">
          <a href="#quotation-section" class="btn btn-primary btn-sm quote-trigger">Request Quote</a>
          <button type="button" class="btn btn-primary-outline btn-sm">View Profile</button>
        </div>
      `;

      card.querySelectorAll(".quote-trigger").forEach((btn) => {
        btn.addEventListener("click", (event) => {
          event.preventDefault();
          scrollToQuotation();
        });
      });

      grid.appendChild(card);
      observeAnimatedElement(card);
    });
  };

  const applyFilters = () => {
    const searchTerm = searchInput?.value.trim().toLowerCase() || "";
    const district = districtFilter?.value || "";
    const service = serviceFilter?.value || "";

    const filtered = directoryInstallers.filter((installer) => {
      const matchesSearch = installer.name.toLowerCase().includes(searchTerm);
      const matchesDistrict = !district || installer.district === district;
      const matchesService = !service || installer.services.includes(service);
      return matchesSearch && matchesDistrict && matchesService;
    });

    renderInstallers(filtered);
  };

  searchInput?.addEventListener("input", applyFilters);
  districtFilter?.addEventListener("change", applyFilters);
  serviceFilter?.addEventListener("change", applyFilters);

  renderInstallers(directoryInstallers);
}

function loadInstallerOptions() {
  const container = document.getElementById("installer-options");
  if (!container) return;

  container.innerHTML = "";

  installersData.forEach((installer) => {
    const card = document.createElement("div");
    card.className = "installer-option";

    card.innerHTML = `
      <div class="installer-card-head">
        <span class="rating"><i class="fas fa-star"></i> ${installer.rating.toFixed(
      1
    )}</span>
        <span class="installer-option__meta">${installer.reviews} reviews</span>
      </div>
      <div class="installer-option__name">${installer.name}</div>
      <div class="installer-option__meta">${installer.experience} • ${installer.region
      }</div>
      <div class="installer-option__price">
        <span>Average rate</span>
        <span>Rs ${(installer.baseRate / 1000).toFixed(0)}k / kW</span>
      </div>
    `;

    card.addEventListener("click", () => selectInstaller(installer, card));
    container.appendChild(card);
    observeAnimatedElement(card);
  });
}

function selectInstaller(installer, cardElement) {
  selectedInstaller = installer;

  document.querySelectorAll(".installer-option").forEach((card) => {
    card.classList.remove("selected");
  });

  cardElement.classList.add("selected");

  quotationState.installer = installer.name;
}

function handleNextStep() {
  if (currentStep === 1 && !selectedInstaller) {
    alert("Please select an installer to continue.");
    return;
  }

  updateStateFromInputs();

  if (currentStep === 4) {
    if (!validateContactDetails()) {
      return;
    }
    submitQuotation();
    return;
  }

  const next = Math.min(currentStep + 1, 5);
  goToStep(next);
}

function handlePrevStep() {
  if (currentStep <= 1) return;
  const prev = Math.max(currentStep - 1, 1);
  goToStep(prev);
}

function goToStep(stepNumber) {
  currentStep = stepNumber;

  document.querySelectorAll(".quotation-step").forEach((step) => {
    const stepValue = Number(step.dataset.step);
    step.classList.toggle("active", stepValue === currentStep);
  });

  document.querySelectorAll(".progress-step").forEach((progressEl) => {
    const progressStep = Number(progressEl.dataset.step);
    progressEl.classList.toggle("active", progressStep === currentStep);
    if (currentStep >= 5) {
      progressEl.classList.add("completed");
    } else if (progressStep < currentStep) {
      progressEl.classList.add("completed");
    } else {
      progressEl.classList.remove("completed");
    }
  });

  if (currentStep === 4) {
    renderConfirmation();
    displayPriceSummary();
  }

  updateNavigationState();
}

function updateNavigationState() {
  const prevBtn = document.getElementById("prev-step-btn");
  const nextBtn = document.getElementById("next-step-btn");
  const footer = document.getElementById("quotation-footer");

  if (!prevBtn || !nextBtn || !footer) return;

  if (currentStep === 1) {
    prevBtn.disabled = true;
    prevBtn.style.visibility = "hidden";
  } else {
    prevBtn.disabled = false;
    prevBtn.style.visibility = "visible";
  }

  if (currentStep === 4) {
    nextBtn.textContent = "Get My Quote";
  } else {
    nextBtn.textContent = "Continue";
  }

  if (currentStep >= 5) {
    footer.classList.add("hidden");
    footer.style.display = "none";
  } else {
    footer.classList.remove("hidden");
    footer.style.display = "";
  }
}

function updateStateFromInputs() {
  const cfg = SOLAR_CONFIG;

  // 1. Bill Amount -> Capacity
  const billAmount = document.getElementById("bill-amount")?.value || "medium";
  quotationState.capacity = cfg.systemSizing.billToCapacity[billAmount] || 5;

  // 2. Usage Pattern
  quotationState.usagePattern = document.getElementById("usage-pattern")?.value || "balanced";

  // 3. Backup Needs -> Battery & Inverter
  const backupNeeds = document.getElementById("backup-needs")?.value || "none";
  const backupConfig = cfg.backupMapping[backupNeeds] || cfg.backupMapping.none;
  quotationState.battery = backupConfig.battery;
  quotationState.inverterType = backupConfig.inverter;

  // 4. Preference -> Panel Type
  const preference = document.getElementById("preference")?.value || "value";
  quotationState.panelType = cfg.preferenceMapping[preference] || "poly";

  // 5. Roof Type
  quotationState.roofType = document.getElementById("roof-type")?.value || "tile";

  // 6. Monitoring (smart-features was removed, default to basic)
  quotationState.monitoring = "basic";
  quotationState.warranty = "10";

  // 7. Heavy Loads (Appliances)
  const applianceCheckboxes = document.querySelectorAll('input[name="appliances"]:checked');
  quotationState.heavyLoads = Array.from(applianceCheckboxes)
    .map(cb => cb.value)
    .filter(v => v !== "none");
}

function calculatePrice() {
  if (!selectedInstaller) return null;

  const cfg = SOLAR_CONFIG;
  const capacity = Number(quotationState.capacity);

  // Base installation cost
  const baseRate = selectedInstaller.baseRate * capacity * selectedInstaller.markup;

  // Apply modifiers from config
  const capacityMultiplier = cfg.baseCosts.capacityMultiplier[capacity] || 1;
  const panelMultiplier = cfg.panels[quotationState.panelType]?.multiplier || 1;
  const inverterMultiplier = cfg.inverters[quotationState.inverterType]?.multiplier || 1;

  const systemCost = baseRate * capacityMultiplier * panelMultiplier * inverterMultiplier;

  // Additional costs from config
  const batteryCost = cfg.batteries[quotationState.battery]?.price || 0;
  const roofCost = cfg.installation.roofType[quotationState.roofType]?.price || 0;
  const monitoringCost = cfg.extras.monitoring[quotationState.monitoring]?.price || 0;
  const warrantyCost = cfg.extras.warranty[quotationState.warranty]?.price || 0;

  // Calculate totals
  const subtotal = systemCost + batteryCost + roofCost + monitoringCost + warrantyCost;
  const tax = subtotal * cfg.fees.taxRate;
  const total = Math.round(subtotal + tax);

  return {
    systemCost: Math.round(systemCost),
    batteryCost,
    roofCost,
    monitoringCost,
    warrantyCost,
    tax: Math.round(tax),
    total,
    // Keep old name for backward compatibility in renderConfirmation
    adjustedBase: Math.round(systemCost),
  };
}

/**
 * Calculate monthly and annual savings
 * Uses SOLAR_CONFIG.savings parameters
 * Applies usage pattern multiplier for realistic savings estimate
 */
function calculateSavings(capacity, usagePattern = "balanced") {
  const cfg = SOLAR_CONFIG.savings;

  // Daily generation = capacity × peak sun hours × performance ratio
  const dailyGeneration = capacity * cfg.peakSunHours * cfg.performanceRatio;

  // Monthly generation (30 days average)
  const monthlyGeneration = dailyGeneration * 30;

  // Monthly savings = generation × tariff rate × usage multiplier
  const multiplier = cfg.usageMultiplier[usagePattern] || 1.0;
  const monthlySavings = Math.round(monthlyGeneration * cfg.electricityTariff * multiplier);

  // Annual savings
  const annualSavings = monthlySavings * 12;

  return {
    dailyGeneration: Math.round(dailyGeneration * 10) / 10,
    monthlyGeneration: Math.round(monthlyGeneration),
    monthlySavings,
    annualSavings,
  };
}

function renderConfirmation() {
  const summaryEl = document.getElementById("confirmation-summary");
  if (!summaryEl) return;

  const state = quotationState;
  const installerName = selectedInstaller ? selectedInstaller.name : "-";

  // Build benefit-focused recommendation (no technical specs)
  let benefitText = "Your system will cover your daily electricity needs";

  if (state.battery !== "none") {
    benefitText += ", power essential appliances during outages";
  }

  benefitText += ", and maximize your solar savings.";

  // Usage pattern benefit
  const usageBenefit = {
    "day": "With most of your usage during the day, you'll get the best value from direct solar power.",
    "night": "Battery storage ensures you save even when using electricity at night.",
    "balanced": "Your balanced usage means consistent savings around the clock."
  };

  // Appliance benefit
  const applianceNames = {
    "ac": "air conditioner",
    "heater": "water heater",
    "washer": "washing machine",
    "cooker": "electric cooker"
  };
  const applianceList = state.heavyLoads.map(a => applianceNames[a]).filter(Boolean);
  const applianceBenefit = applianceList.length > 0
    ? `We've sized this to comfortably power your ${applianceList.join(" and ")}.`
    : "";

  // Calculate key numbers using config-based functions
  const pricing = calculatePrice();
  const capacity = Number(state.capacity);
  const savings = calculateSavings(capacity, state.usagePattern);
  const paybackYears = pricing ? (pricing.total / savings.annualSavings).toFixed(1) : "-";
  const totalInvestment = pricing ? formatCurrency(pricing.total) : "-";
  const monthlySavings = savings.monthlySavings;

  // Confidence indicator
  const hasAllInputs = state.heavyLoads.length > 0 || state.heavyLoads.includes("none");
  const confidenceLevel = hasAllInputs ? "High" : "Medium";

  const appDesc = state.monitoring === "advanced" ? "Premium tracking & alerts" : "Standard included";

  summaryEl.innerHTML = `
    <div class="quote-recommendation">
      <div class="quote-recommendation__header">
        <i class="fas fa-sun"></i>
        <span>Your Solar Solution</span>
      </div>
      <p class="quote-recommendation__text">${benefitText}</p>
      <p class="quote-recommendation__subtext">${usageBenefit[state.usagePattern] || ""} ${applianceBenefit}</p>
    </div>

    <div class="quote-metrics">
      <div class="quote-metric quote-metric--investment">
        <p class="quote-metric__label">Total Investment</p>
        <p class="quote-metric__value quote-metric__value--dark">${totalInvestment}</p>
      </div>
      <div class="quote-metric quote-metric--savings">
        <p class="quote-metric__label">Monthly Savings</p>
        <p class="quote-metric__value quote-metric__value--green">${formatCurrency(monthlySavings)}</p>
      </div>
      <div class="quote-metric quote-metric--payback">
        <p class="quote-metric__label">Payback Period</p>
        <p class="quote-metric__value quote-metric__value--blue">${paybackYears} yrs</p>
      </div>
    </div>

    <div class="quote-confidence">
      <span class="quote-confidence__label">Estimate Confidence:</span>
      <span class="quote-confidence__value ${hasAllInputs ? 'quote-confidence__value--high' : 'quote-confidence__value--medium'}">${confidenceLevel}</span>
    </div>

    <div class="quote-installer">
      <div class="quote-installer__content">
        <div>
          <p class="quote-installer__name">Installed by ${installerName}</p>
          <p class="quote-installer__region">Coverage: ${selectedInstaller?.region || "--"}</p>
        </div>
        <i class="fas fa-check-circle quote-installer__check"></i>
      </div>
    </div>

    <details class="quote-details">
      <summary class="quote-details__summary">
        <span>View cost breakdown</span>
        <i class="fas fa-chevron-down"></i>
      </summary>
      <div class="quote-details__content">
        <div class="quote-details__row">
          <span class="quote-details__label">System & Installation</span>
          <span class="quote-details__value">${pricing ? formatCurrency(pricing.adjustedBase) : "-"}</span>
        </div>
        ${pricing && pricing.batteryCost > 0 ? `
        <div class="quote-details__row">
          <span class="quote-details__label">Battery Backup</span>
          <span class="quote-details__value">${formatCurrency(pricing.batteryCost)}</span>
        </div>` : ""}
        <div class="quote-details__row">
          <span class="quote-details__label">Roof Mounting</span>
          <span class="quote-details__value">${pricing ? formatCurrency(pricing.roofCost) : "-"}</span>
        </div>
        ${pricing && pricing.monitoringCost > 0 ? `
        <div class="quote-details__row">
          <span class="quote-details__label">Smart Monitoring</span>
          <span class="quote-details__value">${formatCurrency(pricing.monitoringCost)}</span>
        </div>` : ""}
        <div class="quote-details__row">
          <span class="quote-details__label">Taxes & Fees</span>
          <span class="quote-details__value">${pricing ? formatCurrency(pricing.tax) : "-"}</span>
        </div>
      </div>
    </details>

    <details class="quote-details">
      <summary class="quote-details__summary">
        <span>What's included</span>
        <i class="fas fa-chevron-down"></i>
      </summary>
      <div class="quote-details__content">
        <div class="quote-details__row">
          <span class="quote-details__label">Roof Mounting</span>
          <span class="quote-details__value">${formatLabel(state.roofType)} compatible</span>
        </div>
        <div class="quote-details__row">
          <span class="quote-details__label">Smart Tracking</span>
          <span class="quote-details__value">${appDesc}</span>
        </div>
        <div class="quote-details__row">
          <span class="quote-details__label">Warranty</span>
          <span class="quote-details__value">${state.warranty} years</span>
        </div>
      </div>
    </details>

    <div class="quote-tip">
      <p class="quote-tip__text">
        <i class="fas fa-lightbulb"></i>
        <strong>Want a more accurate quote?</strong> Our installer will verify appliance types & roof details during the site visit.
      </p>
    </div>
  `;
}

function displayPriceSummary() {
  const priceCard = document.getElementById("price-summary");
  if (!priceCard) return;
  // All pricing content is now rendered in renderConfirmation() for a cleaner, consolidated view
  priceCard.innerHTML = "";
}

function validateContactDetails() {
  const name = document.getElementById("customer-name");
  const email = document.getElementById("customer-email");
  const phone = document.getElementById("customer-phone");

  if (!name?.value.trim() || !email?.value.trim() || !phone?.value.trim()) {
    alert("Please provide your name, email, and phone number.");
    return false;
  }

  const emailPattern = /.+@.+\..+/;
  if (!emailPattern.test(email.value.trim())) {
    alert("Please enter a valid email address.");
    return false;
  }

  return true;
}

function submitQuotation() {
  const pricing = calculatePrice();
  if (!pricing) return;

  const payload = {
    installer: selectedInstaller?.name,
    configuration: { ...quotationState },
    pricing,
    customer: {
      name: document.getElementById("customer-name")?.value.trim() || "",
      email: document.getElementById("customer-email")?.value.trim() || "",
      phone: document.getElementById("customer-phone")?.value.trim() || "",
    },
  };

  console.table(payload);

  const successInstaller = document.getElementById("success-installer");
  if (successInstaller) {
    successInstaller.textContent = selectedInstaller?.name || "your installer";
  }

  goToStep(5);
}

function resetCalculator() {
  selectedInstaller = null;
  quotationState.installer = null;
  quotationState.capacity = "5";
  quotationState.panelType = "mono";
  quotationState.inverterType = "string";
  quotationState.battery = "none";
  quotationState.roofType = "tile";
  quotationState.monitoring = "basic";
  quotationState.warranty = "10";

  const capacity = document.getElementById("capacity");
  const panelType = document.getElementById("panel-type");
  const inverterType = document.getElementById("inverter-type");
  const battery = document.getElementById("battery");
  const roofType = document.getElementById("roof-type");
  const monitoring = document.getElementById("monitoring");
  const warranty = document.getElementById("warranty");

  if (capacity) capacity.value = "5";
  if (panelType) panelType.value = "mono";
  if (inverterType) inverterType.value = "string";
  if (battery) battery.value = "none";
  if (roofType) roofType.value = "tile";
  if (monitoring) monitoring.value = "basic";
  if (warranty) warranty.value = "10";

  document
    .querySelectorAll(".installer-option")
    .forEach((card) => card.classList.remove("selected"));
}

function scrollToQuotation() {
  const section = document.getElementById("quotation-section");
  if (!section) return;
  section.scrollIntoView({ behavior: "smooth", block: "start" });
}

function formatCurrency(value) {
  return `Rs ${Number(value || 0).toLocaleString()}`;
}

function formatLabel(token) {
  if (!token) return "--";
  return token
    .toString()
    .replace(/-/g, " ")
    .replace(/_/g, " ")
    .replace(/\b\w/g, (char) => char.toUpperCase());
}


function selectInstaller(installer, cardElement) {
    // 1. Set the global selected installer state
    selectedInstaller = {
        id: installer.company_id,
        name: installer.company_name,
        baseRate: 85000, // You can later add this to your DB table
        region: installer.district
    };

    // 2. Visual feedback: remove selection from others, add to this one
    document.querySelectorAll(".installer-option").forEach((card) => {
        card.classList.remove("selected");
    });
    cardElement.classList.add("selected");

    // 3. Update the quotation state for final submission
    quotationState.installer = installer.company_name;
    quotationState.company_id = installer.company_id;
}
