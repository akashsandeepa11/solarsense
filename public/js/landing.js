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

const installersData = [
  {
    id: 1,
    name: "SunPower Solutions",
    rating: 4.8,
    reviews: 247,
    baseRate: 85000,
    markup: 1.05,
    experience: "12 yrs",
    region: "Island-wide",
  },
  {
    id: 2,
    name: "GreenEnergy Pro",
    rating: 4.7,
    reviews: 189,
    baseRate: 78000,
    markup: 0.98,
    experience: "9 yrs",
    region: "Western & Southern",
  },
  {
    id: 3,
    name: "EcoSolar Tech",
    rating: 4.9,
    reviews: 312,
    baseRate: 91000,
    markup: 1.08,
    experience: "14 yrs",
    region: "Island-wide",
  },
  {
    id: 4,
    name: "PowerSun Installation",
    rating: 4.6,
    reviews: 156,
    baseRate: 76000,
    markup: 0.95,
    experience: "8 yrs",
    region: "Central & Uva",
  },
];

const pricingConfig = {
  capacity: {
    3: 1.0,
    5: 1.12,
    7: 1.25,
    10: 1.48,
  },
  panelType: {
    mono: 1.1,
    poly: 0.95,
    thin: 0.85,
  },
  inverterType: {
    string: 1.0,
    micro: 1.18,
    hybrid: 1.35,
  },
  battery: {
    none: 0,
    5: 190000,
    10: 340000,
    15: 480000,
  },
  roofType: {
    tile: 25000,
    metal: 18000,
    flat: 32000,
  },
  monitoring: {
    basic: 15000,
    advanced: 42000,
  },
  warranty: {
    10: 0,
    15: 28000,
    25: 65000,
  },
};

let currentStep = 1;
let selectedInstaller = null;

const quotationState = {
  installer: null,
  capacity: "5",
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
  // Mapping Logic
  // 1. Bill Amount -> Capacity
  const billAmount = document.getElementById("bill-amount")?.value || "medium";
  const billMap = {
    low: "3", // < 15k
    medium: "5", // 15-30k
    high: "7", // 30-45k
    "very-high": "10", // > 45k
  };
  quotationState.capacity = billMap[billAmount];

  // 2. Usage Pattern
  quotationState.usagePattern = document.getElementById("usage-pattern")?.value || "balanced";

  // 3. Backup Needs -> Battery & Inverter
  const backupNeeds = document.getElementById("backup-needs")?.value || "none";
  if (backupNeeds === "none") {
    quotationState.battery = "none";
    quotationState.inverterType = "string";
  } else if (backupNeeds === "essentials") {
    quotationState.battery = "5";
    quotationState.inverterType = "hybrid";
  } else {
    // full
    quotationState.battery = "10";
    quotationState.inverterType = "hybrid";
  }

  // 4. Preference -> Panel Type
  const preference = document.getElementById("preference")?.value || "value";
  quotationState.panelType = preference === "value" ? "poly" : "mono";

  // 5. Installation
  quotationState.roofType =
    document.getElementById("roof-type")?.value || "tile";

  const smartFeatures = document.getElementById("smart-features")?.value || "basic";
  quotationState.monitoring = smartFeatures;
  quotationState.warranty = smartFeatures === "advanced" ? "25" : "10";

  // 6. Heavy Loads (Appliances)
  const applianceCheckboxes = document.querySelectorAll('input[name="appliances"]:checked');
  quotationState.heavyLoads = Array.from(applianceCheckboxes)
    .map(cb => cb.value)
    .filter(v => v !== "none");
}

function calculatePrice() {
  if (!selectedInstaller) return null;

  const capacity = Number(quotationState.capacity);
  const baseRate =
    selectedInstaller.baseRate * capacity * selectedInstaller.markup;
  const capacityModifier = pricingConfig.capacity[capacity] || 1;
  const panelModifier = pricingConfig.panelType[quotationState.panelType] || 1;
  const inverterModifier =
    pricingConfig.inverterType[quotationState.inverterType] || 1;

  const adjustedBase =
    baseRate * capacityModifier * panelModifier * inverterModifier;

  const batteryCost = pricingConfig.battery[quotationState.battery] || 0;
  const roofCost = pricingConfig.roofType[quotationState.roofType] || 0;
  const monitoringCost =
    pricingConfig.monitoring[quotationState.monitoring] || 0;
  const warrantyCost = pricingConfig.warranty[quotationState.warranty] || 0;

  const subtotal =
    adjustedBase + batteryCost + roofCost + monitoringCost + warrantyCost;
  const tax = subtotal * 0.08; // estimated VAT & levies
  const total = Math.round(subtotal + tax);

  return {
    adjustedBase: Math.round(adjustedBase),
    batteryCost,
    roofCost,
    monitoringCost,
    warrantyCost,
    tax: Math.round(tax),
    total,
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

  // Calculate key numbers
  const pricing = calculatePrice();
  const capacity = Number(state.capacity);
  const monthlyGeneration = capacity * 4.5 * 30;
  const tariff = 35;
  const monthlySavings = Math.round(monthlyGeneration * tariff);
  const annualSavings = monthlySavings * 12;
  const paybackYears = pricing ? Math.max(pricing.total / annualSavings, 0).toFixed(1) : "-";
  const totalInvestment = pricing ? formatCurrency(pricing.total) : "-";

  // Confidence indicator
  const hasAllInputs = state.heavyLoads.length > 0 || state.heavyLoads.includes("none");
  const confidenceLevel = hasAllInputs ? "High" : "Medium";
  const confidenceColor = hasAllInputs ? "#22c55e" : "#f59e0b";

  const appDesc = state.monitoring === "advanced" ? "Premium tracking & alerts" : "Standard included";

  summaryEl.innerHTML = `
    <div class="recommendation-card" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: none; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem;">
      <div class="recommendation-header" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
        <i class="fas fa-sun" style="color: #fe9630; font-size: 1.25rem;"></i>
        <span style="font-weight: 600; color: #1e293b;">Your Solar Solution</span>
      </div>
      <p style="color: #475569; line-height: 1.6; margin: 0;">${benefitText}</p>
      <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.75rem; margin-bottom: 0;">${usageBenefit[state.usagePattern] || ""} ${applianceBenefit}</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
      <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; text-align: center;">
        <p style="font-size: 0.8rem; color: #64748b; margin: 0 0 0.25rem 0;">Total Investment</p>
        <p style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">${totalInvestment}</p>
      </div>
      <div style="background: #f0fdf4; padding: 1rem; border-radius: 12px; text-align: center;">
        <p style="font-size: 0.8rem; color: #64748b; margin: 0 0 0.25rem 0;">Monthly Savings</p>
        <p style="font-size: 1.25rem; font-weight: 700; color: #22c55e; margin: 0;">${formatCurrency(monthlySavings)}</p>
      </div>
      <div style="background: #eff6ff; padding: 1rem; border-radius: 12px; text-align: center;">
        <p style="font-size: 0.8rem; color: #64748b; margin: 0 0 0.25rem 0;">Payback Period</p>
        <p style="font-size: 1.25rem; font-weight: 700; color: #3b82f6; margin: 0;">${paybackYears} yrs</p>
      </div>
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
      <span style="font-size: 0.85rem; color: #64748b;">Estimate Confidence:</span>
      <span style="font-size: 0.85rem; font-weight: 600; color: ${confidenceColor};">${confidenceLevel}</span>
    </div>

    <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <p style="font-weight: 600; color: #1e293b; margin: 0;">Installed by ${installerName}</p>
          <p style="font-size: 0.85rem; color: #64748b; margin: 0.25rem 0 0 0;">Coverage: ${selectedInstaller?.region || "--"}</p>
        </div>
        <i class="fas fa-check-circle" style="color: #22c55e; font-size: 1.5rem;"></i>
      </div>
    </div>

    <details style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 1rem;">
      <summary style="padding: 1rem; cursor: pointer; font-weight: 500; color: #475569; list-style: none; display: flex; justify-content: space-between; align-items: center;">
        <span>View cost breakdown</span>
        <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: #94a3b8;"></i>
      </summary>
      <div style="padding: 0 1rem 1rem 1rem; border-top: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: #64748b;">System & Installation</span>
          <span style="color: #1e293b;">${pricing ? formatCurrency(pricing.adjustedBase) : "-"}</span>
        </div>
        ${pricing && pricing.batteryCost > 0 ? `
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: #64748b;">Battery Backup</span>
          <span style="color: #1e293b;">${formatCurrency(pricing.batteryCost)}</span>
        </div>` : ""}
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: #64748b;">Roof Mounting</span>
          <span style="color: #1e293b;">${pricing ? formatCurrency(pricing.roofCost) : "-"}</span>
        </div>
        ${pricing && pricing.monitoringCost > 0 ? `
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: #64748b;">Smart Monitoring</span>
          <span style="color: #1e293b;">${formatCurrency(pricing.monitoringCost)}</span>
        </div>` : ""}
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
          <span style="color: #64748b;">Taxes & Fees</span>
          <span style="color: #1e293b;">${pricing ? formatCurrency(pricing.tax) : "-"}</span>
        </div>
      </div>
    </details>

    <details style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 1rem;">
      <summary style="padding: 1rem; cursor: pointer; font-weight: 500; color: #475569; list-style: none; display: flex; justify-content: space-between; align-items: center;">
        <span>What's included</span>
        <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: #94a3b8;"></i>
      </summary>
      <div style="padding: 0 1rem 1rem 1rem; border-top: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: #64748b;">Roof Mounting</span>
          <span style="color: #1e293b;">${formatLabel(state.roofType)} compatible</span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: #64748b;">Smart Tracking</span>
          <span style="color: #1e293b;">${appDesc}</span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
          <span style="color: #64748b;">Warranty</span>
          <span style="color: #1e293b;">${state.warranty} years</span>
        </div>
      </div>
    </details>

    <div style="background: #fef3c7; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem;">
      <p style="font-size: 0.85rem; color: #92400e; margin: 0;">
        <i class="fas fa-lightbulb" style="margin-right: 0.5rem;"></i>
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
