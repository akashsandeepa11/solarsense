# Solar Quotation - How the Calculation Works

This document explains step-by-step how the quotation calculator determines the **Total Investment**, **Monthly Savings**, and **Payback Period**.

---

## Overview

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│  User Inputs    │ →  │  Calculate Price │ →  │  Final Quote    │
│  (4 Steps)      │    │  + Savings       │    │  Display        │
└─────────────────┘    └──────────────────┘    └─────────────────┘
```

---

## Step 1: Map User Inputs to System Configuration

When user fills the form, their choices are converted to technical values:

| User Input | Maps To | Example |
|------------|---------|---------|
| Monthly Bill = "Rs 15-30k" | Capacity = **5 kW** | Medium bill → 5kW system |
| Backup = "Essentials" | Battery = **5 kWh**, Inverter = **Hybrid** | Needs backup → add battery |
| Preference = "Value" | Panel = **Polycrystalline** | Budget option |
| Roof = "Tile" | Roof Cost = **Rs 25,000** | Standard mounting |

---

## Step 2: Calculate Total Investment

### Formula
```
Total Investment = System Cost + Additional Costs + Tax
```

### 2.1 System Cost

```
System Cost = Base Rate × Capacity × Capacity Multiplier × Panel Multiplier × Inverter Multiplier
```

| Component | Value | Description |
|-----------|-------|-------------|
| **Base Rate** | Rs 85,000/kW | Installer's rate (varies by installer) |
| **Capacity** | 5 kW | System size based on bill |
| **Capacity Multiplier** | 1.0 | Larger systems get discounts |
| **Panel Multiplier** | 1.0 (poly) or 1.1 (mono) | Premium panels cost more |
| **Inverter Multiplier** | 1.0 (string) or 1.25 (hybrid) | Hybrid needed for battery |

**Example: 5kW Poly system with String inverter**
```
System Cost = 85,000 × 5 × 1.0 × 1.0 × 1.0
            = Rs 425,000
```

**Example: 5kW Mono system with Hybrid inverter (for battery)**
```
System Cost = 85,000 × 5 × 1.0 × 1.1 × 1.25
            = Rs 584,375
```

### 2.2 Additional Costs

| Item | Cost | When Added |
|------|------|------------|
| **Battery** | Rs 180,000 (5kWh) or Rs 320,000 (10kWh) | If backup selected |
| **Roof Mounting** | Rs 18,000 - 30,000 | Always (depends on roof type) |
| **Monitoring** | Rs 12,000 | Always included (basic) |
| **Extended Warranty** | Rs 0 - 55,000 | Optional |

### 2.3 Tax

```
Tax = Subtotal × 8%
```

### 2.4 Complete Example

**User selects:**
- Bill: Rs 15-30k (→ 5kW)
- Backup: None (→ String inverter, no battery)
- Preference: Value (→ Poly panels)
- Roof: Tile
- Installer: SunPower Solutions (Rs 85,000/kW)

```
System Cost     = 85,000 × 5 × 1.0 × 1.0 × 1.0  = Rs 425,000
+ Battery       =                                  Rs       0
+ Roof Mounting =                                  Rs  25,000
+ Monitoring    =                                  Rs  12,000
+ Warranty      =                                  Rs       0
─────────────────────────────────────────────────────────────
  Subtotal      =                                  Rs 462,000
+ Tax (8%)      =                                  Rs  36,960
─────────────────────────────────────────────────────────────
  TOTAL         =                                  Rs 498,960
```

---

## Step 3: Calculate Monthly Savings

### Formula
```
Monthly Savings = Daily Generation × 30 days × Electricity Rate × Usage Multiplier
```

Where:
```
Daily Generation = Capacity × Peak Sun Hours × Performance Ratio
```

### Parameters

| Parameter | Value | Description |
|-----------|-------|-------------|
| **Capacity** | 5 kW | System size |
| **Peak Sun Hours** | 4.5 hrs | Average in Sri Lanka |
| **Performance Ratio** | 0.80 (80%) | Real-world efficiency |
| **Electricity Rate** | Rs 32/kWh | Average CEB tariff |
| **Usage Multiplier** | 0.8 - 1.0 | Based on day/night usage pattern |

### Usage Pattern Multipliers

| Usage Pattern | Multiplier | Explanation |
|---------------|------------|-------------|
| **Day** | 0.9 (90%) | Most usage during daytime = best direct solar utilization |
| **Balanced** | 1.0 (100%) | Even usage = baseline savings |
| **Night** | 0.8 (80%) | Most usage at night = more grid dependency, less direct solar use |

### Calculation

**Example: 5kW system with Balanced usage**
```
Daily Generation = 5 kW × 4.5 hrs × 0.80
                 = 18 kWh per day

Monthly Generation = 18 × 30 = 540 kWh

Monthly Savings = 540 × Rs 32 × 1.0 = Rs 17,280
```

**Example: 5kW system with Night usage**
```
Daily Generation = 5 kW × 4.5 hrs × 0.80
                 = 18 kWh per day

Monthly Generation = 18 × 30 = 540 kWh

Monthly Savings = 540 × Rs 32 × 0.8 = Rs 13,824
```

---

## Step 4: Calculate Payback Period

### Formula
```
Payback Period = Total Investment ÷ Annual Savings
```

### Calculation
```
Annual Savings = Monthly Savings × 12
               = 17,280 × 12
               = Rs 207,360

Payback Period = 498,960 ÷ 207,360
               = 2.4 years
```

---

## Complete Worked Example

### Scenario: Home with high electricity usage and need for backup

**User Inputs:**
- Monthly Bill: Rs 30-45k (high)
- Backup: Essentials (lights, fans, router)
- Preference: Performance (premium panels)
- Roof: Flat concrete
- Installer: EcoSolar Tech (Rs 91,000/kW)

**Step 1: Map to Configuration**
```
Capacity     = 7 kW (high bill)
Panel Type   = Mono (performance)
Inverter     = Hybrid (for battery)
Battery      = 5 kWh (essentials backup)
```

**Step 2: Calculate Investment**
```
System Cost:
= 91,000 × 7 × 0.95 × 1.1 × 1.25
= 91,000 × 7 × 1.30625
= Rs 832,109

Additional Costs:
+ Battery (5kWh)      = Rs 180,000
+ Roof (flat)         = Rs  30,000
+ Monitoring (basic)  = Rs  12,000
+ Warranty (10yr)     = Rs       0
────────────────────────────────────
Subtotal              = Rs 1,054,109
+ Tax (8%)            = Rs    84,329
────────────────────────────────────
TOTAL INVESTMENT      = Rs 1,138,438
```

**Step 3: Calculate Savings**
```
Daily Generation = 7 × 4.5 × 0.80 = 25.2 kWh
Monthly Generation = 25.2 × 30 = 756 kWh
Monthly Savings = 756 × 32 = Rs 24,192
Annual Savings = Rs 290,304
```

**Step 4: Calculate Payback**
```
Payback = 1,138,438 ÷ 290,304 = 3.9 years
```

---

## Summary of Key Formulas

| Calculation | Formula |
|-------------|---------|
| **System Cost** | `BaseRate × Capacity × CapacityMult × PanelMult × InverterMult` |
| **Total Investment** | `SystemCost + Battery + Roof + Monitoring + Warranty + Tax` |
| **Daily Generation** | `Capacity × 4.5 hrs × 0.80` |
| **Monthly Savings** | `DailyGeneration × 30 × Rs 32 × usageMultiplier` |
| **Payback Period** | `TotalInvestment ÷ (MonthlySavings × 12)` |

---

## Where to Update Values

All values are in `SOLAR_CONFIG` object in `landing.js`:

```javascript
// To change electricity rate:
SOLAR_CONFIG.savings.electricityTariff = 32;  // Change this

// To change sun hours:
SOLAR_CONFIG.savings.peakSunHours = 4.5;  // Change this

// To change usage multipliers:
SOLAR_CONFIG.savings.usageMultiplier = {
  day: 0.9,      // Day usage multiplier
  balanced: 1.0, // Balanced usage multiplier
  night: 0.8,    // Night usage multiplier
};

// To change battery prices:
SOLAR_CONFIG.batteries["5"].price = 180000;  // Change this

// To change tax rate:
SOLAR_CONFIG.fees.taxRate = 0.08;  // Change this (8%)
```
