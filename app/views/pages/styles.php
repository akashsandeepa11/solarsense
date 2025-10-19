<?php
// filepath: c:\xampp\htdocs\solarsense\app\views\pages\styles.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Custom CSS Library - Documentation</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/main.css" />
    <style>
        .docs-main { display: flex; }
        .docs-sidebar { flex: 0 0 260px; position: sticky; top: 0; height: 100vh; background-color: #fff; border-right: 1px solid #dee2e6; overflow-y: auto; }
        .docs-sidebar .nav-link { display: block; padding: 0.5rem 1.5rem; color: #495057; text-decoration: none; transition: all 0.15s ease-in-out; border-left: 3px solid transparent; }
        .docs-sidebar .nav-link:hover { color: #fe9630; background-color: #f8f9fa; border-left-color: #fe9630; }
        .docs-sidebar .nav-title { padding: 1.5rem 1.5rem 0.5rem; font-size: 0.875rem; font-weight: 600; color: #6c757d; text-transform: uppercase; }
        .docs-content { flex: 1; padding: 2rem 3rem; }
        .docs-section { margin-bottom: 4rem; }
        .docs-section h2 { font-size: 2.25rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #dee2e6; }
        .docs-section h3 { font-size: 1.5rem; margin-top: 2rem; margin-bottom: 1rem; }
        .docs-example { background-color: #fff; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #e9ecef; margin-bottom: 1rem; }
        pre { background-color: #2d2d2d; color: #f8f8f2; padding: 1rem; border-radius: 0.5rem; font-family: Consolas, "Fira Code", monospace; white-space: pre-wrap; word-wrap: break-word; }
        code { font-family: Consolas, "Fira Code", monospace; }
        .swatch-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; }
        .color-swatch { display: flex; align-items: center; justify-content: center; height: 100px; border-radius: 0.75rem; color: #fff; font-weight: 600; }
        .color-swatch.light { color: #212121; border: 1px solid #e9ecef; }
        .grid-demo .col-1,.grid-demo .col-2,.grid-demo .col-3,.grid-demo .col-4,.grid-demo .col-6,.grid-demo .col-12 { background:#f1f3f5; border: 1px dashed #dee2e6; padding: .5rem; text-align: center; }
        .inline-demo > span { display:inline-block; padding:.25rem .5rem; background:#f8f9fa; border:1px solid #e9ecef; border-radius:.25rem; }
    </style>
</head>
<body>

<main class="docs-main">
    <!-- Sidebar -->
    <aside class="docs-sidebar">
        <div class="p-4">
            <h5 class="text-primary">CSS Library</h5>
            <p class="text-sm">Documentation</p>
        </div>
        <nav>
            <div class="nav-title">Getting Started</div>
            <a href="#introduction" class="nav-link">Introduction</a>

            <div class="nav-title">Layout</div>
            <a href="#containers" class="nav-link">Containers</a>
            <a href="#grid" class="nav-link">Grid System</a>

            <div class="nav-title">Content</div>
            <a href="#colors" class="nav-link">Colors</a>
            <a href="#typography" class="nav-link">Typography</a>

            <div class="nav-title">Components</div>
            <a href="#buttons" class="nav-link">Buttons</a>
            <a href="#cards" class="nav-link">Cards</a>
            <a href="#forms" class="nav-link">Forms</a>
            <a href="#floating-input" class="nav-link">Floating Input</a>

            <div class="nav-title">Utilities</div>
            <a href="#display" class="nav-link">Display</a>
            <a href="#flex" class="nav-link">Flexbox</a>
            <a href="#spacing" class="nav-link">Spacing</a>
            <a href="#gap" class="nav-link">Gap</a>
            <a href="#responsive" class="nav-link">Responsive</a>
            <a href="#text-utils" class="nav-link">Text Utilities</a>
            <a href="#borders" class="nav-link">Borders & Radius</a>
            <a href="#shadows" class="nav-link">Shadows</a>
            <a href="#sizing" class="nav-link">Width & Height</a>
            <a href="#position" class="nav-link">Position</a>
            <a href="#overflow" class="nav-link">Overflow</a>
            <a href="#visibility" class="nav-link">Visibility</a>
            <a href="#opacity-z" class="nav-link">Opacity & Z-Index</a>
            <a href="#gradients" class="nav-link">Gradients</a>
            <a href="#focus" class="nav-link">Focus Helpers</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="docs-content">
        <!-- Introduction -->
        <section id="introduction" class="docs-section">
            <h2>Introduction</h2>
            <p class="text-base">This is a live documentation for your custom CSS library. It includes a grid system, components (buttons, cards, forms), and many utility classes with responsive variants.</p>
            <p>Responsive utility syntax uses breakpoint prefixes like <code>sm:</code>, <code>md:</code>, <code>lg:</code>, <code>xl:</code>, <code>2xl:</code> (e.g., <code>sm:m-4</code>). Write them exactly like that in HTML; the compiled CSS handles necessary escaping.</p>
        </section>

        <!-- Containers -->
        <section id="containers" class="docs-section">
            <h2>Containers</h2>
            <div class="docs-example">
                <div class="container">
                    <div class="p-3 bg-surface border rounded-md">.container (max-width per breakpoint)</div>
                </div>
                <div class="container-fluid mt-2">
                    <div class="p-3 bg-surface border rounded-md">.container-fluid (full width)</div>
                </div>
            </div>
            <pre><code>&lt;div class="container"&gt;...&lt;/div&gt;
&lt;div class="container-fluid"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Grid System -->
        <section id="grid" class="docs-section">
            <h2>Grid System</h2>
            <p>12-column flex grid with responsive columns.</p>
            <div class="docs-example grid-demo">
                <div class="row">
                    <div class="col-4">.col-4</div>
                    <div class="col-4">.col-4</div>
                    <div class="col-4">.col-4</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">.col-6</div>
                    <div class="col-3">.col-3</div>
                    <div class="col-3">.col-3</div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6 col-md-4 col-lg-3">.col-sm-6 .col-md-4 .col-lg-3</div>
                    <div class="col-sm-6 col-md-4 col-lg-3">.col-sm-6 .col-md-4 .col-lg-3</div>
                    <div class="col-sm-6 col-md-4 col-lg-3">.col-sm-6 .col-md-4 .col-lg-3</div>
                    <div class="col-sm-6 col-md-12 col-lg-3">.col-sm-6 .col-md-12 .col-lg-3</div>
                </div>
            </div>
            <pre><code>&lt;div class="row"&gt;
  &lt;div class="col-4"&gt;...&lt;/div&gt;
  &lt;div class="col-4"&gt;...&lt;/div&gt;
  &lt;div class="col-4"&gt;...&lt;/div&gt;
&lt;/div&gt;</code></pre>
        </section>

        <!-- Colors -->
        <section id="colors" class="docs-section">
            <h2>Colors</h2>
            <p>Use <code>.bg-*</code> and <code>.text-*</code> with keys from your palette: primary, secondary, accent, success, warning, error, surface, text, background.</p>
            <div class="docs-example">
                <div class="swatch-grid">
                    <div class="color-swatch bg-primary">bg-primary</div>
                    <div class="color-swatch bg-secondary">bg-secondary</div>
                    <div class="color-swatch bg-accent">bg-accent</div>
                    <div class="color-swatch bg-success">bg-success</div>
                    <div class="color-swatch bg-warning">bg-warning</div>
                    <div class="color-swatch bg-error">bg-error</div>
                    <div class="color-swatch bg-surface light">bg-surface</div>
                    <div class="color-swatch bg-text">bg-text</div>
                    <div class="color-swatch bg-background light">bg-background</div>
                </div>
            </div>
            <pre><code>&lt;div class="bg-primary text-surface"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Typography -->
        <section id="typography" class="docs-section">
            <h2>Typography</h2>
            <div class="docs-example">
                <h1>Heading 1</h1>
                <h2>Heading 2</h2>
                <h3>Heading 3</h3>
                <p class="text-base">Use <code>.text-[xs|sm|base|lg|xl|2xl|3xl|4xl|5xl|6xl]</code> and <code>.font-[thin|light|normal|medium|semibold|bold|extrabold|black]</code>.</p>
            </div>
            <pre><code>&lt;p class="text-lg font-semibold"&gt;Text&lt;/p&gt;</code></pre>
        </section>

        <!-- Buttons -->
        <section id="buttons" class="docs-section">
            <h2>Buttons</h2>
            <div class="docs-example d-flex flex-wrap align-center" style="gap:.5rem;">
                <button class="btn btn-primary">Primary</button>
                <button class="btn btn-secondary">Secondary</button>
                <button class="btn btn-accent">Accent</button>
                <button class="btn btn-success">Success</button>
                <button class="btn btn-warning">Warning</button>
                <button class="btn btn-error">Error</button>
                <button class="btn btn-primary btn-sm">Small</button>
                <button class="btn btn-primary btn-lg">Large</button>
                <button class="btn btn-primary btn-block">Block</button>
            </div>
            <pre><code>&lt;button class="btn btn-primary btn-lg"&gt;Large&lt;/button&gt;</code></pre>
        </section>

        <!-- Cards -->
        <section id="cards" class="docs-section">
            <h2>Cards</h2>
            <div class="docs-example">
                <div class="card" style="max-width: 420px;">
                    <div class="card-header"><h5 class="card-title mb-0">Card Title</h5></div>
                    <div class="card-body">
                        <p class="card-text">Card body text</p>
                        <a href="#" class="btn btn-accent">Action</a>
                    </div>
                    <div class="card-footer">Footer</div>
                </div>
            </div>
            <pre><code>&lt;div class="card"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Forms -->
        <section id="forms" class="docs-section">
            <h2>Forms</h2>
            <div class="docs-example">
                <div class="form-group">
                    <label class="form-label" for="f1">Email</label>
                    <input id="f1" type="email" class="form-control" placeholder="you@example.com" />
                </div>
                <div class="form-group">
                    <label class="form-label" for="f2">Password</label>
                    <input id="f2" type="password" class="form-control" placeholder="********" />
                </div>
            </div>
            <pre><code>&lt;input type="email" class="form-control"&gt;</code></pre>
        </section>

        <!-- Floating Input -->
        <section id="floating-input" class="docs-section">
            <h2>Floating Input</h2>
            <p>Floating label with optional left icon. Uses <code>.input</code>, <code>.input__field</code>, <code>.input__label</code>, and optional <code>.input__icon</code>. Use <code>placeholder=" "</code> to enable :placeholder-shown.</p>
            <div class="" style="max-width: 360px;">
                <?php
                // With icon
                $inputConfig = [
                    'id'    => 'doc_input_email',
                    'name'  => 'doc_email',
                    'label' => 'Email Address',
                    'type'  => 'email',
                    'icon'  => 'fa fa-regular fa-envelope'
                ];
                require APPROOT . '/views/inc/components/input_field.php';

                echo '<div class="mt-2"></div>';

                // Without icon
                $inputConfig = [
                    'id'    => 'doc_input_name',
                    'name'  => 'doc_name',
                    'label' => 'Your Name',
                    'type'  => 'text'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <br><br>
            </div>
            <pre><code>&lt;?php
$inputConfig = [
  'id' =&gt; 'doc_input_email',
  'name' =&gt; 'doc_email',
  'label' =&gt; 'Email Address',
  'type' =&gt; 'email',
  'icon' =&gt; 'fa-regular fa-envelope'
];
require APPROOT . '/views/inc/components/input_field.php';

$inputConfig = [
  'id' =&gt; 'doc_input_name',
  'name' =&gt; 'doc_name',
  'label' =&gt; 'Your Name',
  'type' =&gt; 'text'
];
require APPROOT . '/views/inc/components/input_field.php';
?&gt;</code></pre>
        </section>

        <!-- Display -->
        <section id="display" class="docs-section">
            <h2>Display</h2>
            <p>Base: <code>.d-none</code>, <code>.d-inline</code>, <code>.d-inline-block</code>, <code>.d-block</code>, <code>.d-flex</code>, <code>.d-inline-flex</code>, <code>.d-grid</code>, <code>.d-table</code>. Responsive: <code>.d-sm-block</code>, <code>.d-md-flex</code>, etc.</p>
            <div class="docs-example inline-demo">
                <span class="d-inline-block">.d-inline-block</span>
                <span class="d-block mt-1">.d-block</span>
                <span class="d-none sm:d-inline-block mt-1">.d-none sm:d-inline-block</span>
            </div>
            <pre><code>&lt;div class="d-none d-sm-block"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Flexbox -->
        <section id="flex" class="docs-section">
            <h2>Flexbox</h2>
            <p>Direction: <code>.flex-row|.flex-column|.flex-row-reverse|.flex-column-reverse</code>. Wrap: <code>.flex-wrap|.flex-nowrap|.flex-wrap-reverse</code>. Justify: <code>.justify-*</code>. Align: <code>.align-*</code>. Flex: <code>.flex-1|.flex-auto|.flex-initial|.flex-none</code>.</p>
            <div class="docs-example d-flex justify-between align-center">
                <span class="p-2 bg-secondary text-surface rounded-sm">A</span>
                <span class="p-2 bg-secondary text-surface rounded-sm">B</span>
                <span class="p-2 bg-secondary text-surface rounded-sm">C</span>
            </div>
            <pre><code>&lt;div class="d-flex justify-between align-center"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Spacing -->
        <section id="spacing" class="docs-section">
            <h2>Spacing</h2>
            <p>Use <code>m-*</code>, <code>p-*</code> and directional variants: <code>t,r,b,l,x,y</code>. Example: <code>.mt-4</code>, <code>.px-2</code>. Responsive: <code>sm:m-4</code>, <code>md:px-6</code>.</p>
            <div class="docs-example">
                <div class="p-4 bg-primary text-surface rounded-md">.p-4</div>
                <div class="mt-2 p-2 bg-secondary text-surface rounded-md">.mt-2 .p-2</div>
            </div>
            <pre><code>&lt;div class="sm:mt-2 md:mt-6 lg:px-6"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Gap -->
        <section id="gap" class="docs-section">
            <h2>Gap</h2>
            <p>Flex/Grid gaps: <code>.gap-*</code>, <code>.gap-x-*</code>, <code>.gap-y-*</code> and responsive equivalents.</p>
            <div class="docs-example d-grid gap-4" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
                <div class="p-3 bg-accent text-surface rounded-md">1</div>
                <div class="p-3 bg-accent text-surface rounded-md">2</div>
                <div class="p-3 bg-accent text-surface rounded-md">3</div>
            </div>
            <pre><code>&lt;div class="d-grid gap-4 sm:gap-6"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Responsive -->
        <section id="responsive" class="docs-section">
            <h2>Responsive Utilities</h2>
            <p>Prefix utilities with <code>sm:</code>, <code>md:</code>, <code>lg:</code>, <code>xl:</code>, <code>2xl:</code>.</p>
            <div class="docs-example">
                <div class="p-2 sm:p-4 md:p-6 bg-primary text-surface rounded-md">Padding grows on sm and md</div>
                <div class="mt-2 text-left sm:text-center md:text-right">Text alignment changes by breakpoint</div>
                <div class="mt-2 d-flex sm:justify-center md:justify-between gap-2 sm:gap-4">
                    <span class="p-2 bg-secondary text-surface rounded-sm">A</span>
                    <span class="p-2 bg-secondary text-surface rounded-sm">B</span>
                    <span class="p-2 bg-secondary text-surface rounded-sm">C</span>
                </div>
            </div>
            <pre><code>&lt;div class="text-left sm:text-center md:text-right"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Text Utilities -->
        <section id="text-utils" class="docs-section">
            <h2>Text Utilities</h2>
            <p>Alignment: <code>.text-left|.text-center|.text-right|.text-justify</code>. Decoration: <code>.text-underline</code>, <code>.text-line-through</code>, <code>.text-decoration-none</code>, and <code>.hover:no-underline</code>. Truncate: <code>.text-truncate</code>. Colors: <code>.text-primary</code>, etc.</p>
            <div class="docs-example">
                <p class="text-underline">Underlined</p>
                <p class="text-line-through">Line through</p>
                <a class="text-decoration-none hover:no-underline" href="#">No underline on hover</a>
                <div class="text-truncate" style="max-width:220px;">A very long line that will truncate with ellipsis</div>
            </div>
            <pre><code>&lt;a class="text-decoration-none hover:no-underline"&gt;...&lt;/a&gt;</code></pre>
        </section>

        <!-- Borders & Radius -->
        <section id="borders" class="docs-section">
            <h2>Borders & Radius</h2>
            <p>Border: <code>.border</code>, <code>.border-0</code>, <code>.border-[top|right|bottom|left]</code>, and <code>.border-[color]</code>. Radius: <code>.rounded-[none|sm|base|md|lg|xl|2xl|3xl|full]</code>.</p>
            <div class="docs-example d-flex flex-wrap align-center" style="gap:1rem;">
                <div class="p-3 bg-surface border rounded-none">.rounded-none</div>
                <div class="p-3 bg-surface border rounded-md">.rounded-md</div>
                <div class="p-3 bg-surface border rounded-xl">.rounded-xl</div>
                <div class="p-3 bg-surface border rounded-full">.rounded-full</div>
            </div>
            <pre><code>&lt;div class="border border-primary rounded-lg"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Shadows -->
        <section id="shadows" class="docs-section">
            <h2>Shadows</h2>
            <p>Use <code>.shadow-[none|sm|base|md|lg|xl|2xl|inner]</code>.</p>
            <div class="docs-example d-flex flex-wrap align-center" style="gap:1rem;">
                <div class="p-3 bg-surface rounded-md shadow-sm">.shadow-sm</div>
                <div class="p-3 bg-surface rounded-md shadow-md">.shadow-md</div>
                <div class="p-3 bg-surface rounded-md shadow-lg">.shadow-lg</div>
                <div class="p-3 bg-surface rounded-md shadow-2xl">.shadow-2xl</div>
                <div class="p-3 bg-surface rounded-md shadow-inner">.shadow-inner</div>
            </div>
            <pre><code>&lt;div class="shadow-lg"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Width & Height -->
        <section id="sizing" class="docs-section">
            <h2>Width & Height</h2>
            <p>Width: <code>.w-25|.w-50|.w-75|.w-100|.w-auto</code>. Height: <code>.h-25|.h-50|.h-75|.h-100|.h-auto</code>.</p>
            <div class="docs-example d-flex align-center" style="gap:1rem;">
                <div class="w-25 p-2 bg-secondary text-surface rounded-sm text-center">w-25</div>
                <div class="w-50 p-2 bg-secondary text-surface rounded-sm text-center">w-50</div>
                <div class="w-100 p-2 bg-secondary text-surface rounded-sm text-center">w-100</div>
            </div>
            <pre><code>&lt;div class="w-50 h-25"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Position -->
        <section id="position" class="docs-section">
            <h2>Position</h2>
            <p><code>.position-[static|relative|absolute|fixed|sticky]</code>.</p>
            <div class="docs-example position-relative" style="height:120px;">
                <div class="position-absolute p-2 bg-accent text-surface rounded-sm" style="top:10px;left:10px;">absolute</div>
            </div>
            <pre><code>&lt;div class="position-relative"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Overflow -->
        <section id="overflow" class="docs-section">
            <h2>Overflow</h2>
            <p><code>.overflow-auto|.overflow-hidden|.overflow-visible|.overflow-scroll</code>.</p>
            <div class="docs-example overflow-auto" style="max-height:80px;">
                <div style="height:140px;">Scrollable content...</div>
            </div>
            <pre><code>&lt;div class="overflow-auto"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Visibility -->
        <section id="visibility" class="docs-section">
            <h2>Visibility</h2>
            <p>Show/Hide via breakpoints: <code>.hidden-[breakpoint]-up</code> and <code>.hidden-[breakpoint]-down</code>. Example: <code>.hidden-md-up</code> hides on ≥ md. <code>.hidden-md-down</code> hides on &lt; next breakpoint.</p>
            <div class="docs-example">
                <div class="p-2 bg-warning rounded-sm hidden-md-up">Hidden on md and up</div>
                <div class="p-2 bg-success text-surface rounded-sm mt-2 hidden-sm-down">Hidden below next breakpoint after sm</div>
            </div>
            <pre><code>&lt;div class="hidden-lg-up"&gt;...&lt;/div&gt;
&lt;div class="hidden-sm-down"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Opacity & Z-Index -->
        <section id="opacity-z" class="docs-section">
            <h2>Opacity & Z-Index</h2>
            <div class="docs-example d-flex align-center gap-4">
                <div class="p-3 bg-primary text-surface rounded-md opacity-50">.opacity-50</div>
                <div class="position-relative" style="height:70px;">
                    <div class="position-absolute p-2 bg-accent text-surface rounded-sm" style="top:10px; left:10px;">base</div>
                    <div class="position-absolute p-2 bg-secondary text-surface rounded-sm z-50" style="top:22px; left:22px;">.z-50</div>
                </div>
            </div>
            <pre><code>&lt;div class="opacity-50"&gt;...&lt;/div&gt;
&lt;div class="position-absolute z-50"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Gradients -->
        <section id="gradients" class="docs-section">
            <h2>Gradients</h2>
            <div class="docs-example d-flex gap-4">
                <div class="p-4 rounded-md bg-gradient-primary-accent">.bg-gradient-primary-accent</div>
                <div class="p-4 rounded-md bg-gradient-secondary-primary">.bg-gradient-secondary-primary</div>
            </div>
            <pre><code>&lt;div class="bg-gradient-primary-accent p-4 rounded-md"&gt;...&lt;/div&gt;</code></pre>
        </section>

        <!-- Focus Helpers -->
        <section id="focus" class="docs-section">
            <h2>Focus Helpers</h2>
            <p>Helpers: <code>.focus:outline-none</code>, <code>.focus:border-primary</code>, <code>.focus:ring-primary</code>, <code>.focus-within:ring-primary</code>.</p>
            <div class="docs-example d-flex gap-4">
                <input class="form-control focus:outline-none focus:border-primary" placeholder="Primary border on focus" />
                <div class="p-3 rounded-md bg-surface focus-within:ring-primary">
                    <input class="form-control" placeholder="Focus-within ring" />
                </div>
            </div>
            <pre><code>&lt;input class="form-control focus:outline-none focus:border-primary"&gt;</code></pre>
        </section>
    </div>
</main>

</body>
</html>