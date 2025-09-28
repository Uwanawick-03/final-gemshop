@extends('layouts.app')

@section('title', 'Advanced Calculator')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-calculator me-2"></i>Advanced Calculator</h2>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary" onclick="showCalculator('basic')">
            <i class="fas fa-calculator me-1"></i>Basic
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="showCalculator('jewelry')">
            <i class="fas fa-gem me-1"></i>Jewelry
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="showCalculator('gemstone')">
            <i class="fas fa-diamond me-1"></i>Gemstone
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="showCalculator('profit')">
            <i class="fas fa-chart-line me-1"></i>Profit
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="showCalculator('converter')">
            <i class="fas fa-exchange-alt me-1"></i>Converter
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="showCalculator('installment')">
            <i class="fas fa-credit-card me-1"></i>Installment
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="showCalculator('history')">
            <i class="fas fa-history me-1"></i>History
        </button>
    </div>
</div>

<!-- Basic Calculator -->
<div id="basic-calculator" class="calculator-section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Basic Calculator</h5>
                </div>
                <div class="card-body">
                    <div class="calculator">
                        <div class="display mb-3">
                            <input type="text" class="form-control form-control-lg text-end" id="display" readonly>
                        </div>
                        <div class="row g-2">
                            <div class="col-3"><button class="btn btn-secondary w-100" onclick="clearDisplay()">C</button></div>
                            <div class="col-3"><button class="btn btn-secondary w-100" onclick="deleteLast()">⌫</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100" onclick="appendToDisplay('/')">/</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100" onclick="appendToDisplay('*')">×</button></div>
                            
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('7')">7</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('8')">8</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('9')">9</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100" onclick="appendToDisplay('-')">-</button></div>
                            
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('4')">4</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('5')">5</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('6')">6</button></div>
                            <div class="col-3"><button class="btn btn-warning w-100" onclick="appendToDisplay('+')">+</button></div>
                            
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('1')">1</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('2')">2</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('3')">3</button></div>
                            <div class="col-3 row-span-2"><button class="btn btn-primary w-100" style="height: 100px;" onclick="calculate()">=</button></div>
                            
                            <div class="col-6"><button class="btn btn-light w-100" onclick="appendToDisplay('0')">0</button></div>
                            <div class="col-3"><button class="btn btn-light w-100" onclick="appendToDisplay('.')">.</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Calculations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tax Calculator</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control" id="taxAmount" placeholder="Amount" step="0.01">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" id="taxRate" placeholder="Rate %" step="0.01">
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary w-100 mt-2" onclick="calculateTax()">Calculate Tax</button>
                        <div class="mt-2">
                            <small>Tax: <span id="taxResult">$0.00</span></small><br>
                            <small>Total: <span id="taxTotal">$0.00</span></small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Discount Calculator</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control" id="discountAmount" placeholder="Price" step="0.01">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" id="discountPercent" placeholder="Discount %" step="0.01">
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary w-100 mt-2" onclick="calculateDiscount()">Calculate Discount</button>
                        <div class="mt-2">
                            <small>Savings: <span id="discountSavings">$0.00</span></small><br>
                            <small>Final: <span id="discountFinal">$0.00</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jewelry Calculator -->
<div id="jewelry-calculator" class="calculator-section" style="display: none;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-gem me-2"></i>Jewelry Cost Calculator</h5>
                </div>
                <div class="card-body">
                    <form id="jewelryCalcForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Metal Type</label>
                                    <select class="form-select" id="metalType" required>
                                        <option value="gold">Gold</option>
                                        <option value="silver">Silver</option>
                                        <option value="platinum">Platinum</option>
                                        <option value="palladium">Palladium</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Purity (%)</label>
                                    <input type="number" class="form-control" id="purity" value="75" step="0.1" min="1" max="100" required>
                                    <small class="text-muted">e.g., 75% for 18K gold (18/24 = 75%)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Weight (grams)</label>
                                    <input type="number" class="form-control" id="weight" step="0.001" min="0.001" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Metal Price per Gram</label>
                                    <input type="number" class="form-control" id="metalPrice" step="0.01" min="0.01" required>
                                    <small class="text-muted">Current market price</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Labor Cost</label>
                                    <input type="number" class="form-control" id="laborCost" step="0.01" min="0" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Markup (%)</label>
                                    <input type="number" class="form-control" id="markupPercentage" step="0.01" min="0" max="1000" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select" id="jewelryCurrency" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ $currency->is_base_currency ? 'selected' : '' }}>
                                                {{ $currency->code }} - {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="calculateJewelry()">
                            <i class="fas fa-calculator me-2"></i>Calculate Jewelry Cost
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Current Metal Prices</h6>
                </div>
                <div class="card-body">
                    @foreach(['gold_24k' => 'Gold 24K', 'gold_18k' => 'Gold 18K', 'gold_14k' => 'Gold 14K', 'silver' => 'Silver', 'platinum' => 'Platinum'] as $key => $label)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $label }}</span>
                        <strong>${{ number_format($metalPrices[$key], 2) }}/g</strong>
                    </div>
                    @endforeach
                    <hr>
                    <small class="text-muted">Prices updated hourly</small>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Calculation Result</h6>
                </div>
                <div class="card-body" id="jewelryResult">
                    <div class="text-center text-muted">
                        <i class="fas fa-gem fa-2x mb-2"></i>
                        <p>Enter details and calculate to see results</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gemstone Calculator -->
<div id="gemstone-calculator" class="calculator-section" style="display: none;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-diamond me-2"></i>Gemstone Value Calculator</h5>
                </div>
                <div class="card-body">
                    <form id="gemstoneCalcForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Gemstone Type</label>
                                    <select class="form-select" id="gemstoneType" required>
                                        <option value="diamond">Diamond</option>
                                        <option value="ruby">Ruby</option>
                                        <option value="sapphire">Sapphire</option>
                                        <option value="emerald">Emerald</option>
                                        <option value="amethyst">Amethyst</option>
                                        <option value="topaz">Topaz</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Carat Weight</label>
                                    <input type="number" class="form-control" id="caratWeight" step="0.01" min="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price per Carat</label>
                                    <input type="number" class="form-control" id="pricePerCarat" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Clarity Grade</label>
                                    <select class="form-select" id="clarity">
                                        <option value="">Select Clarity</option>
                                        <option value="FL">FL (Flawless)</option>
                                        <option value="IF">IF (Internally Flawless)</option>
                                        <option value="VVS1">VVS1</option>
                                        <option value="VVS2">VVS2</option>
                                        <option value="VS1">VS1</option>
                                        <option value="VS2">VS2</option>
                                        <option value="SI1">SI1</option>
                                        <option value="SI2">SI2</option>
                                        <option value="I1">I1</option>
                                        <option value="I2">I2</option>
                                        <option value="I3">I3</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Color Grade</label>
                                    <select class="form-select" id="colorGrade">
                                        <option value="">Select Color</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                        <option value="G">G</option>
                                        <option value="H">H</option>
                                        <option value="I">I</option>
                                        <option value="J">J</option>
                                        <option value="K">K</option>
                                        <option value="L">L</option>
                                        <option value="M">M</option>
                                        <option value="N">N</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Cut Grade</label>
                                    <select class="form-select" id="cutGrade">
                                        <option value="">Select Cut</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Very Good">Very Good</option>
                                        <option value="Good">Good</option>
                                        <option value="Fair">Fair</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Setting Cost</label>
                                    <input type="number" class="form-control" id="settingCost" step="0.01" min="0" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select" id="gemstoneCurrency" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ $currency->is_base_currency ? 'selected' : '' }}>
                                                {{ $currency->code }} - {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="calculateGemstone()">
                            <i class="fas fa-calculator me-2"></i>Calculate Gemstone Value
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Gemstone Quality Guide</h6>
                </div>
                <div class="card-body">
                    <h6>Clarity Grades:</h6>
                    <ul class="list-unstyled small">
                        <li><strong>FL/IF:</strong> Flawless/Internally Flawless</li>
                        <li><strong>VVS:</strong> Very, Very Slightly Included</li>
                        <li><strong>VS:</strong> Very Slightly Included</li>
                        <li><strong>SI:</strong> Slightly Included</li>
                        <li><strong>I:</strong> Included</li>
                    </ul>
                    
                    <h6>Color Grades:</h6>
                    <ul class="list-unstyled small">
                        <li><strong>D-F:</strong> Colorless</li>
                        <li><strong>G-J:</strong> Near Colorless</li>
                        <li><strong>K-M:</strong> Faint Yellow</li>
                        <li><strong>N-R:</strong> Very Light Yellow</li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Calculation Result</h6>
                </div>
                <div class="card-body" id="gemstoneResult">
                    <div class="text-center text-muted">
                        <i class="fas fa-diamond fa-2x mb-2"></i>
                        <p>Enter details and calculate to see results</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profit Margin Calculator -->
<div id="profit-calculator" class="calculator-section" style="display: none;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Profit Margin Calculator</h5>
                </div>
                <div class="card-body">
                    <form id="profitCalcForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cost Price</label>
                                    <input type="number" class="form-control" id="costPrice" step="0.01" min="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Selling Price</label>
                                    <input type="number" class="form-control" id="sellingPrice" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Additional Costs</label>
                                    <input type="number" class="form-control" id="additionalCosts" step="0.01" min="0" value="0">
                                    <small class="text-muted">Marketing, shipping, etc.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select" id="profitCurrency" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ $currency->is_base_currency ? 'selected' : '' }}>
                                                {{ $currency->code }} - {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="calculateProfit()">
                            <i class="fas fa-calculator me-2"></i>Calculate Profit Margin
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Calculation Result</h6>
                </div>
                <div class="card-body" id="profitResult">
                    <div class="text-center text-muted">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p>Enter details and calculate to see results</p>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Profit Margin Guide</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-success">Excellent: >50%</span>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-info">Good: 30-50%</span>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-warning">Fair: 15-30%</span>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-danger">Poor: <15%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unit Converter -->
<div id="converter-calculator" class="calculator-section" style="display: none;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Unit Converter</h5>
                </div>
                <div class="card-body">
                    <form id="converterForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Unit Type</label>
                                    <select class="form-select" id="unitType" onchange="updateUnitOptions()" required>
                                        <option value="weight">Weight</option>
                                        <option value="length">Length</option>
                                        <option value="temperature">Temperature</option>
                                        <option value="volume">Volume</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">From Unit</label>
                                    <select class="form-select" id="fromUnit" required>
                                        <option value="gram">Gram</option>
                                        <option value="kilogram">Kilogram</option>
                                        <option value="pound">Pound</option>
                                        <option value="ounce">Ounce</option>
                                        <option value="carat">Carat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Value</label>
                                    <input type="number" class="form-control" id="converterValue" step="0.001" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">To Unit</label>
                                    <select class="form-select" id="toUnit" required>
                                        <option value="gram">Gram</option>
                                        <option value="kilogram">Kilogram</option>
                                        <option value="pound">Pound</option>
                                        <option value="ounce">Ounce</option>
                                        <option value="carat">Carat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100" onclick="convertUnits()">
                                        <i class="fas fa-exchange-alt me-2"></i>Convert
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Conversion Result</h6>
                </div>
                <div class="card-body" id="converterResult">
                    <div class="text-center text-muted">
                        <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                        <p>Enter values and convert</p>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Common Conversions</h6>
                </div>
                <div class="card-body">
                    <h6>Weight:</h6>
                    <ul class="list-unstyled small">
                        <li>1 gram = 5 carats</li>
                        <li>1 ounce = 28.35 grams</li>
                        <li>1 pound = 453.59 grams</li>
                    </ul>
                    
                    <h6>Length:</h6>
                    <ul class="list-unstyled small">
                        <li>1 inch = 25.4 mm</li>
                        <li>1 foot = 304.8 mm</li>
                        <li>1 meter = 1000 mm</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Installment Calculator -->
<div id="installment-calculator" class="calculator-section" style="display: none;">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Installment Payment Calculator</h5>
                </div>
                <div class="card-body">
                    <form id="installmentCalcForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Principal Amount</label>
                                    <input type="number" class="form-control" id="principalAmount" step="0.01" min="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Interest Rate (%)</label>
                                    <input type="number" class="form-control" id="interestRate" step="0.01" min="0" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Number of Installments</label>
                                    <input type="number" class="form-control" id="installmentPeriods" min="1" max="120" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Payment Frequency</label>
                                    <select class="form-select" id="paymentFrequency" required>
                                        <option value="monthly">Monthly</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="daily">Daily</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select" id="installmentCurrency" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ $currency->is_base_currency ? 'selected' : '' }}>
                                                {{ $currency->code }} - {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Additional Fees</label>
                                    <input type="number" class="form-control" id="additionalFees" step="0.01" min="0" value="0">
                                    <small class="text-muted">Processing fees, insurance, etc.</small>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="calculateInstallment()">
                            <i class="fas fa-calculator me-2"></i>Calculate Installment Plan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Installment Plan Result</h6>
                </div>
                <div class="card-body" id="installmentResult">
                    <div class="text-center text-muted">
                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                        <p>Enter details and calculate to see results</p>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Payment Schedule Preview</h6>
                </div>
                <div class="card-body" id="paymentSchedule">
                    <div class="text-center text-muted">
                        <small>Payment schedule will appear here</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calculation History -->
<div id="history-calculator" class="calculator-section" style="display: none;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Calculation History</h5>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-history fa-3x mb-3"></i>
                        <h5>Calculation History</h5>
                        <p>Your recent calculations will appear here. This feature helps you keep track of your previous calculations for reference.</p>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-gem fa-2x text-primary mb-2"></i>
                                        <h6>Jewelry Calculations</h6>
                                        <p class="small text-muted">Track your jewelry cost calculations</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                                        <h6>Profit Analysis</h6>
                                        <p class="small text-muted">Monitor your profit margins</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <i class="fas fa-credit-card fa-2x text-warning mb-2"></i>
                                        <h6>Payment Plans</h6>
                                        <p class="small text-muted">Review installment calculations</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calculator-section {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.calculator .btn {
    font-size: 1.1rem;
    font-weight: 500;
}

.calculator .btn-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.calculator .btn-light:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.result-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}

.result-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.result-item:last-child {
    border-bottom: none;
    font-weight: bold;
    font-size: 1.1rem;
}
</style>

<script>
// Basic Calculator Functions
let display = document.getElementById('display');
let currentInput = '';

function appendToDisplay(value) {
    currentInput += value;
    display.value = currentInput;
}

function clearDisplay() {
    currentInput = '';
    display.value = '';
}

function deleteLast() {
    currentInput = currentInput.slice(0, -1);
    display.value = currentInput;
}

function calculate() {
    try {
        let result = eval(currentInput.replace('×', '*'));
        display.value = result;
        currentInput = result.toString();
    } catch (error) {
        display.value = 'Error';
        currentInput = '';
    }
}

// Calculator Navigation
function showCalculator(type) {
    // Hide all sections
    document.querySelectorAll('.calculator-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Show selected section
    document.getElementById(type + '-calculator').style.display = 'block';
    
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

// Quick Calculations
function calculateTax() {
    const amount = parseFloat(document.getElementById('taxAmount').value) || 0;
    const rate = parseFloat(document.getElementById('taxRate').value) || 0;
    const tax = amount * (rate / 100);
    document.getElementById('taxResult').textContent = '$' + tax.toFixed(2);
    document.getElementById('taxTotal').textContent = '$' + (amount + tax).toFixed(2);
}

function calculateDiscount() {
    const amount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const percent = parseFloat(document.getElementById('discountPercent').value) || 0;
    const savings = amount * (percent / 100);
    document.getElementById('discountSavings').textContent = '$' + savings.toFixed(2);
    document.getElementById('discountFinal').textContent = '$' + (amount - savings).toFixed(2);
}

// Jewelry Calculator
function calculateJewelry() {
    const formData = {
        metal_type: document.getElementById('metalType').value,
        purity: parseFloat(document.getElementById('purity').value),
        weight: parseFloat(document.getElementById('weight').value),
        metal_price: parseFloat(document.getElementById('metalPrice').value),
        labor_cost: parseFloat(document.getElementById('laborCost').value) || 0,
        markup_percentage: parseFloat(document.getElementById('markupPercentage').value) || 0,
        currency_id: document.getElementById('jewelryCurrency').value
    };
    
    fetch('{{ route("calculator.jewelry") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        displayJewelryResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error calculating jewelry cost. Please try again.');
    });
}

function displayJewelryResult(data) {
    const resultHtml = `
        <div class="result-card p-3">
            <div class="result-item">
                <span>Metal Value:</span>
                <span>${data.currency_symbol}${data.metal_value}</span>
            </div>
            <div class="result-item">
                <span>Labor Cost:</span>
                <span>${data.currency_symbol}${data.labor_cost}</span>
            </div>
            <div class="result-item">
                <span>Subtotal:</span>
                <span>${data.currency_symbol}${data.subtotal}</span>
            </div>
            <div class="result-item">
                <span>Markup:</span>
                <span>${data.currency_symbol}${data.markup_amount}</span>
            </div>
            <div class="result-item">
                <span>Total Cost:</span>
                <span>${data.currency_symbol}${data.total}</span>
            </div>
        </div>
    `;
    document.getElementById('jewelryResult').innerHTML = resultHtml;
}

// Gemstone Calculator
function calculateGemstone() {
    const formData = {
        gemstone_type: document.getElementById('gemstoneType').value,
        carat_weight: parseFloat(document.getElementById('caratWeight').value),
        price_per_carat: parseFloat(document.getElementById('pricePerCarat').value),
        clarity: document.getElementById('clarity').value,
        color_grade: document.getElementById('colorGrade').value,
        cut_grade: document.getElementById('cutGrade').value,
        setting_cost: parseFloat(document.getElementById('settingCost').value) || 0,
        currency_id: document.getElementById('gemstoneCurrency').value
    };
    
    fetch('{{ route("calculator.gemstone") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        displayGemstoneResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error calculating gemstone value. Please try again.');
    });
}

function displayGemstoneResult(data) {
    const resultHtml = `
        <div class="result-card p-3">
            <div class="result-item">
                <span>Base Value:</span>
                <span>${data.currency_symbol}${data.base_value}</span>
            </div>
            <div class="result-item">
                <span>Quality Multiplier:</span>
                <span>${data.quality_multiplier}x</span>
            </div>
            <div class="result-item">
                <span>Adjusted Value:</span>
                <span>${data.currency_symbol}${data.adjusted_value}</span>
            </div>
            <div class="result-item">
                <span>Setting Cost:</span>
                <span>${data.currency_symbol}${data.setting_cost}</span>
            </div>
            <div class="result-item">
                <span>Total Value:</span>
                <span>${data.currency_symbol}${data.total}</span>
            </div>
        </div>
    `;
    document.getElementById('gemstoneResult').innerHTML = resultHtml;
}

// Profit Calculator
function calculateProfit() {
    const formData = {
        cost_price: parseFloat(document.getElementById('costPrice').value),
        selling_price: parseFloat(document.getElementById('sellingPrice').value),
        additional_costs: parseFloat(document.getElementById('additionalCosts').value) || 0
    };
    
    fetch('{{ route("calculator.profit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        displayProfitResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error calculating profit margin. Please try again.');
    });
}

function displayProfitResult(data) {
    const marginClass = data.profit_margin >= 30 ? 'text-success' : 
                       data.profit_margin >= 15 ? 'text-warning' : 'text-danger';
    
    const resultHtml = `
        <div class="p-3">
            <div class="result-item">
                <span>Cost Price:</span>
                <span>$${data.cost_price}</span>
            </div>
            <div class="result-item">
                <span>Additional Costs:</span>
                <span>$${data.additional_costs}</span>
            </div>
            <div class="result-item">
                <span>Total Cost:</span>
                <span>$${data.total_cost}</span>
            </div>
            <div class="result-item">
                <span>Selling Price:</span>
                <span>$${data.selling_price}</span>
            </div>
            <div class="result-item">
                <span>Gross Profit:</span>
                <span>$${data.gross_profit}</span>
            </div>
            <div class="result-item">
                <span>Profit Margin:</span>
                <span class="${marginClass}">${data.profit_margin}%</span>
            </div>
            <div class="result-item">
                <span>Markup:</span>
                <span>${data.markup_percentage}%</span>
            </div>
        </div>
    `;
    document.getElementById('profitResult').innerHTML = resultHtml;
}

// Unit Converter
function updateUnitOptions() {
    const unitType = document.getElementById('unitType').value;
    const fromUnit = document.getElementById('fromUnit');
    const toUnit = document.getElementById('toUnit');
    
    const unitOptions = {
        weight: ['gram', 'kilogram', 'pound', 'ounce', 'carat', 'grain'],
        length: ['millimeter', 'centimeter', 'meter', 'inch', 'foot', 'yard'],
        temperature: ['celsius', 'fahrenheit', 'kelvin'],
        volume: ['milliliter', 'liter', 'cubic_centimeter', 'fluid_ounce', 'cup', 'pint']
    };
    
    const options = unitOptions[unitType] || [];
    
    // Update from unit options
    fromUnit.innerHTML = options.map(unit => 
        `<option value="${unit}">${unit.charAt(0).toUpperCase() + unit.slice(1).replace('_', ' ')}</option>`
    ).join('');
    
    // Update to unit options
    toUnit.innerHTML = options.map(unit => 
        `<option value="${unit}">${unit.charAt(0).toUpperCase() + unit.slice(1).replace('_', ' ')}</option>`
    ).join('');
}

function convertUnits() {
    const formData = {
        value: parseFloat(document.getElementById('converterValue').value),
        from_unit: document.getElementById('fromUnit').value,
        to_unit: document.getElementById('toUnit').value,
        unit_type: document.getElementById('unitType').value
    };
    
    fetch('{{ route("calculator.convert") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        displayConverterResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error converting units. Please try again.');
    });
}

function displayConverterResult(data) {
    const resultHtml = `
        <div class="text-center p-3">
            <h4>${data.original_value} ${data.original_unit}</h4>
            <i class="fas fa-arrow-down fa-2x my-3 text-primary"></i>
            <h4 class="text-primary">${data.converted_value} ${data.converted_unit}</h4>
        </div>
    `;
    document.getElementById('converterResult').innerHTML = resultHtml;
}

// Installment Calculator
function calculateInstallment() {
    const formData = {
        principal_amount: parseFloat(document.getElementById('principalAmount').value),
        interest_rate: parseFloat(document.getElementById('interestRate').value),
        installment_periods: parseInt(document.getElementById('installmentPeriods').value),
        payment_frequency: document.getElementById('paymentFrequency').value,
        currency_id: document.getElementById('installmentCurrency').value
    };
    
    fetch('{{ route("calculator.installment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        displayInstallmentResult(data);
        generatePaymentSchedule(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error calculating installment plan. Please try again.');
    });
}

function displayInstallmentResult(data) {
    const resultHtml = `
        <div class="p-3">
            <div class="result-item">
                <span>Principal Amount:</span>
                <span>${data.currency_symbol}${data.principal_amount}</span>
            </div>
            <div class="result-item">
                <span>Interest Rate:</span>
                <span>${data.interest_rate}%</span>
            </div>
            <div class="result-item">
                <span>Installment Amount:</span>
                <span class="text-primary fw-bold">${data.currency_symbol}${data.installment_amount}</span>
            </div>
            <div class="result-item">
                <span>Total Payments:</span>
                <span>${data.currency_symbol}${data.total_payments}</span>
            </div>
            <div class="result-item">
                <span>Total Interest:</span>
                <span class="text-warning">${data.currency_symbol}${data.total_interest}</span>
            </div>
        </div>
    `;
    document.getElementById('installmentResult').innerHTML = resultHtml;
}

function generatePaymentSchedule(data) {
    const scheduleHtml = `
        <div class="small">
            <div class="d-flex justify-content-between mb-1">
                <span>Payment Frequency:</span>
                <span class="text-capitalize">${data.payment_frequency}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Number of Payments:</span>
                <span>${data.installment_periods}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Each Payment:</span>
                <span class="fw-bold">${data.currency_symbol}${data.installment_amount}</span>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between">
                <span>Total Cost:</span>
                <span class="text-primary fw-bold">${data.currency_symbol}${data.total_payments}</span>
            </div>
        </div>
    `;
    document.getElementById('paymentSchedule').innerHTML = scheduleHtml;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateUnitOptions();
});
</script>
@endsection
