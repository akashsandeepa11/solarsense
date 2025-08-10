<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom CSS Library - Documentation</title>
    
    <!-- Google Fonts: Poppins & Fira Code -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code&display=swap" rel="stylesheet">

    <!-- Link to your Custom CSS Library -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/main.css">

    <!-- Documentation Specific Styles -->
    <style>
        .docs-main { display: flex; }
        .docs-sidebar { flex: 0 0 260px; position: sticky; top: 0; height: 100vh; background-color: #fff; border-right: 1px solid #dee2e6; overflow-y: auto; }
        .docs-sidebar .nav-link { display: block; padding: 0.5rem 1.5rem; color: #495057; text-decoration: none; transition: all 0.15s ease-in-out; border-left: 3px solid transparent; }
        .docs-sidebar .nav-link:hover { color: var(--color-primary, #fe9630); background-color: #f8f9fa; border-left-color: var(--color-primary, #fe9630); }
        .docs-sidebar .nav-title { padding: 1.5rem 1.5rem 0.5rem; font-size: 0.875rem; font-weight: 600; color: #6c757d; text-transform: uppercase; }
        .docs-content { flex: 1; padding: 2rem 3rem; }
        .docs-section { margin-bottom: 4rem; }
        .docs-section h2 { font-size: 2.25rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #dee2e6; }
        .docs-section h3 { font-size: 1.5rem; margin-top: 2rem; margin-bottom: 1rem; }
        .docs-example { background-color: #fff; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #e9ecef; margin-bottom: 1rem; }
        pre { background-color: #2d2d2d; color: #f8f8f2; padding: 1rem; border-radius: 0.5rem; font-family: "Fira Code", Consolas, monospace; white-space: pre-wrap; word-wrap: break-word; }
        code { font-family: "Fira Code", Consolas, monospace; }
        .color-swatch { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 120px; height: 120px; border-radius: 0.75rem; color: white; font-weight: 500; text-shadow: 0 1px 2px rgba(0,0,0,0.2); }
        .color-swatch.light { color: var(--color-text, #212121); text-shadow: none; border: 1px solid #e9ecef;}
        .swatch-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; }
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
                <a href="#grid" class="nav-link">Grid System</a>
                
                <div class="nav-title">Content</div>
                <a href="#colors" class="nav-link">Colors</a>
                <a href="#typography" class="nav-link">Typography</a>
                
                <div class="nav-title">Components</div>
                <a href="#buttons" class="nav-link">Buttons</a>
                <a href="#cards" class="nav-link">Cards</a>
                <a href="#forms" class="nav-link">Forms</a>
                
                <div class="nav-title">Utilities</div>
                <a href="#spacing" class="nav-link">Spacing</a>
                <a href="#shadows" class="nav-link">Shadows</a>
                <a href="#borders" class="nav-link">Borders</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="docs-content">
            <!-- Introduction -->
            <section id="introduction" class="docs-section">
                <h2>Introduction</h2>
                <p class="text-lg">Welcome to the documentation for your custom CSS library. This library provides a comprehensive set of styles, components, and utilities to build modern and responsive web interfaces quickly.</p>
                <p>This entire documentation page is built using the library itself, serving as a live demonstration of its features. Use the sidebar to navigate through the different sections.</p>
            </section>
            
            <!-- Grid System -->
            <section id="grid" class="docs-section">
                <h2>Grid System</h2>
                <p>A responsive, 12-column, flexbox-based grid system. Use <code>.container</code> for a responsive fixed-width layout or <code>.container-fluid</code> for a full-width layout. Structure your content with <code>.row</code> and <code>.col-*</code> classes.</p>
                <h3>Example</h3>
                <div class="docs-example">
                    <div class="row">
                        <div class="col-4"><div class="p-3 bg-secondary text-surface rounded-md text-center">.col-4</div></div>
                        <div class="col-4"><div class="p-3 bg-secondary text-surface rounded-md text-center">.col-4</div></div>
                        <div class="col-4"><div class="p-3 bg-secondary text-surface rounded-md text-center">.col-4</div></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6"><div class="p-3 bg-secondary text-surface rounded-md text-center">.col-6</div></div>
                        <div class="col-3"><div class="p-3 bg-secondary text-surface rounded-md text-center">.col-3</div></div>
                        <div class="col-3"><div class="p-3 bg-secondary text-surface rounded-md text-center">.col-3</div></div>
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
                <p>The color palette can be applied to backgrounds with <code>.bg-*</code> classes and text with <code>.text-*</code> classes.</p>
                <div class="docs-example">
                    <div class="swatch-grid">
                        <div class="color-swatch bg-primary">Primary</div>
                        <div class="color-swatch bg-secondary">Secondary</div>
                        <div class="color-swatch bg-accent">Accent</div>
                        <div class="color-swatch bg-success">Success</div>
                        <div class="color-swatch bg-warning">Warning</div>
                        <div class="color-swatch bg-error">Error</div>
                        <div class="color-swatch bg-surface light">Surface</div>
                        <div class="color-swatch bg-text">Text</div>
                        <div class="color-swatch bg-background light">Background</div>
                    </div>
                </div>
                <pre><code>&lt;div class="bg-primary text-surface"&gt;...&lt;/div&gt;</code></pre>
            </section>

            <!-- Typography -->
            <section id="typography" class="docs-section">
                <h2>Typography</h2>
                <p>Includes styles for headings, paragraphs, links, and various text utility classes for alignment, weight, and size.</p>
                <div class="docs-example">
                    <h1>Heading 1</h1>
                    <h2>Heading 2</h2>
                    <h3>Heading 3</h3>
                    <p class="text-base">This is a base paragraph. It uses the primary font family. You can easily change font weight using classes like <span class="font-bold">.font-bold</span> or change the size with classes like <span class="text-lg">.text-lg</span>. This is a <a href="#">sample link</a>.</p>
                </div>
                <pre><code>&lt;h1&gt;Heading 1&lt;/h1&gt;
&lt;p class="font-bold"&gt;Bold text.&lt;/p&gt;</code></pre>
            </section>

            <!-- Buttons -->
            <section id="buttons" class="docs-section">
                <h2>Buttons</h2>
                <p>A variety of button styles and sizes are available. Use <code>.btn</code> as the base class, and modify it with <code>.btn-*</code> for color and <code>.btn-*</code> for size.</p>
                <h3>Variants</h3>
                <div class="docs-example d-flex flex-wrap align-center" style="gap: 0.5rem;">
                    <button class="btn btn-primary">Primary</button>
                    <button class="btn btn-secondary">Secondary</button>
                    <button class="btn btn-accent">Accent</button>
                    <button class="btn btn-success">Success</button>
                    <button class="btn btn-warning">Warning</button>
                    <button class="btn btn-error">Error</button>
                </div>
                <pre><code>&lt;button class="btn btn-primary"&gt;Primary&lt;/button&gt;</code></pre>
                <h3>Sizes</h3>
                <div class="docs-example d-flex flex-wrap align-center" style="gap: 0.5rem;">
                    <button class="btn btn-primary btn-sm">Small</button>
                    <button class="btn btn-primary">Default</button>
                    <button class="btn btn-primary btn-lg">Large</button>
                </div>
                <pre><code>&lt;button class="btn btn-primary btn-lg"&gt;Large&lt;/button&gt;</code></pre>
            </section>
            
            <!-- Cards -->
            <section id="cards" class="docs-section">
                <h2>Cards</h2>
                <p>A flexible content container with options for headers, footers, and body content.</p>
                <div class="docs-example">
                    <div class="card" style="max-width: 400px;">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Card Title</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">This is some example text within the card body. Cards are great for grouping related information.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                        <div class="card-footer">
                            Card Footer
                        </div>
                    </div>
                </div>
                <pre><code>&lt;div class="card"&gt;
    &lt;div class="card-header"&gt;...&lt;/div&gt;
    &lt;div class="card-body"&gt;...&lt;/div&gt;
    &lt;div class="card-footer"&gt;...&lt;/div&gt;
&lt;/div&gt;</code></pre>
            </section>

            <!-- Forms -->
            <section id="forms" class="docs-section">
                <h2>Forms</h2>
                <p>Styles for basic form controls and labels.</p>
                <div class="docs-example">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <pre><code>&lt;div class="form-group"&gt;
    &lt;label class="form-label"&gt;...&lt;/label&gt;
    &lt;input type="email" class="form-control"&gt;
&lt;/div&gt;</code></pre>
            </section>

            <!-- Spacing -->
            <section id="spacing" class="docs-section">
                <h2>Spacing</h2>
                <p>Responsive margin (<code>m</code>) and padding (<code>p</code>) utilities. The format is <code>{property}{sides}-{size}</code>. For example, <code>.mt-4</code> for margin-top or <code>.px-2</code> for horizontal padding.</p>
                <div class="docs-example">
                    <div class="p-4 bg-primary text-surface rounded-md">.p-4</div>
                    <div class="mt-2 p-2 bg-secondary text-surface rounded-md">.mt-2 .p-2</div>
                </div>
                <pre><code>&lt;div class="p-4"&gt;...&lt;/div&gt;
&lt;div class="mt-2"&gt;...&lt;/div&gt;</code></pre>
            </section>

            <!-- Shadows -->
            <section id="shadows" class="docs-section">
                <h2>Shadows</h2>
                <p>Apply box-shadows to elements using <code>.shadow-*</code> classes.</p>
                <div class="docs-example d-flex flex-wrap" style="gap: 1rem;">
                    <div class="p-4 rounded-lg shadow-sm bg-surface">.shadow-sm</div>
                    <div class="p-4 rounded-lg shadow-base bg-surface">.shadow-base</div>
                    <div class="p-4 rounded-lg shadow-md bg-surface">.shadow-md</div>
                    <div class="p-4 rounded-lg shadow-lg bg-surface">.shadow-lg</div>
                </div>
                <pre><code>&lt;div class="shadow-lg"&gt;...&lt;/div&gt;</code></pre>
            </section>

            <!-- Borders -->
            <section id="borders" class="docs-section">
                <h2>Borders</h2>
                <p>Use <code>.rounded-*</code> classes to apply border-radius.</p>
                <div class="docs-example d-flex flex-wrap align-center" style="gap: 1rem;">
                    <div class="w-25 p-4 bg-accent text-surface rounded-sm">.rounded-sm</div>
                    <div class="w-25 p-4 bg-accent text-surface rounded-lg">.rounded-lg</div>
                    <div class="w-25 p-4 bg-accent text-surface rounded-3xl">.rounded-3xl</div>
                    <div style="width: 100px; height: 100px;" class="bg-accent rounded-full d-flex align-center justify-center text-surface">.rounded-full</div>
                </div>
                <pre><code>&lt;div class="rounded-lg"&gt;...&lt;/div&gt;</code></pre>
            </section>

        </div>
    </main>

</body>
</html>
