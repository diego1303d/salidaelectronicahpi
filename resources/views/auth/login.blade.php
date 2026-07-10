<!DOCTYPE html><html class="light" lang="es"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Iniciar Sesión | Saldias electronicas</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;family=JetBrains+Mono&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "on-surface": "#161a32",
                      "error": "#ba1a1a",
                      "secondary-fixed-dim": "#f5bf22",
                      "outline": "#717973",
                      "surface-tint": "#3f6653",
                      "surface": "#fbf8ff",
                      "surface-container-low": "#f4f2ff",
                      "secondary": "#775a00",
                      "tertiary": "#002d1c",
                      "inverse-on-surface": "#f0efff",
                      "on-tertiary": "#ffffff",
                      "on-error": "#ffffff",
                      "on-primary-container": "#86af99",
                      "on-secondary-fixed-variant": "#5a4300",
                      "on-surface-variant": "#414844",
                      "on-tertiary-fixed-variant": "#0e5138",
                      "surface-container": "#ececff",
                      "on-secondary": "#ffffff",
                      "primary-container": "#1b4332",
                      "surface-variant": "#dee0ff",
                      "inverse-primary": "#a5d0b9",
                      "tertiary-fixed": "#b1f0ce",
                      "on-tertiary-container": "#75b393",
                      "on-tertiary-fixed": "#002114",
                      "error-container": "#ffdad6",
                      "secondary-fixed": "#ffdf98",
                      "on-secondary-fixed": "#251a00",
                      "primary-fixed-dim": "#a5d0b9",
                      "on-secondary-container": "#6f5400",
                      "surface-bright": "#fbf8ff",
                      "surface-container-lowest": "#ffffff",
                      "on-background": "#161a32",
                      "secondary-container": "#fec72c",
                      "on-error-container": "#93000a",
                      "on-primary-fixed-variant": "#274e3d",
                      "surface-dim": "#d5d8f9",
                      "tertiary-fixed-dim": "#95d4b3",
                      "outline-variant": "#c1c8c2",
                      "on-primary": "#ffffff",
                      "surface-container-high": "#e5e6ff",
                      "inverse-surface": "#2b2f48",
                      "on-primary-fixed": "#002114",
                      "primary": "#012d1d",
                      "tertiary-container": "#00452e",
                      "surface-container-highest": "#dee0ff",
                      "primary-fixed": "#c1ecd4",
                      "background": "#fbf8ff"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "lg": "48px",
                      "base": "8px",
                      "sm": "12px",
                      "container-max": "1440px",
                      "gutter": "24px",
                      "xs": "4px",
                      "md": "24px",
                      "xl": "80px"
              },
              "fontFamily": {
                      "display-lg": ["Inter"],
                      "body-sm": ["Inter"],
                      "title-md": ["Inter"],
                      "data-mono": ["JetBrains Mono"],
                      "body-lg": ["Inter"],
                      "headline-lg-mobile": ["Inter"],
                      "label-caps": ["Inter"],
                      "headline-lg": ["Inter"]
              },
              "fontSize": {
                      "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                      "title-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "data-mono": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                      "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                      "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}]
              }
            },
          },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .grain-overlay {
            background-image: url("https://www.transparenttextures.com/patterns/asfalt-light.png");
            opacity: 0.03;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-surface font-body-lg text-on-surface antialiased">
<!-- As per instructions, Navigation Shell is suppressed for login page -->
<main class="min-h-screen flex flex-col md:flex-row overflow-hidden">
<!-- Left Side: Visual Anchor -->
<section class="hidden md:flex md:w-1/2 relative overflow-hidden bg-primary-container">
<div class="absolute inset-0 z-10 bg-gradient-to-t from-primary-container/80 via-primary-container/20 to-transparent"></div>
<div class="absolute inset-0 z-0 scale-105 transition-transform duration-[10000ms] hover:scale-100 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBcQahVP4q8tkk05H4BEvllAosQfhkNL8H3i8k94PXjVabbdS4u09IA6QAZ44kdQmbxGE9-PmHtj41wNUf-T_rSbzeuaoe5-rf21ordanNc_xG7V3_kwhlH2L7skERkR9tqEXKp4Q_B4XthZgTh9yUQkxq9A6A0xlZr6c-cpIQoGhYa7q6VJkpyJp0WJ8ERzbXpLBxHUi-eYDPs951C3yMFRLMMntvw-DQK8SXn1k9ZuIlZkp3h7hS_Dg')"></div>
<div class="relative z-20 flex flex-col justify-end p-lg w-full h-full">
<div class="max-w-xl">
<h1 class="font-display-lg text-display-lg text-white mb-base"></h1>
<p class="font-body-lg text-body-lg text-on-primary-container/90 max-w-md">

                    </p>
</div>
<!-- Subtle visual accent -->
<div class="mt-xl flex items-center gap-base">
<div class="h-[1px] w-12 bg-secondary-fixed"></div>
<span class="font-label-caps text-label-caps text-secondary-fixed uppercase tracking-widest">Harinera los pirineos</span>
</div>
</div>
<!-- Atmospheric noise overlay -->
<div class="absolute inset-0 grain-overlay z-15"></div>
</section>
<!-- Right Side: Login Canvas -->
<section class="w-full md:w-1/2 flex flex-col bg-surface-container-lowest relative">
<div class="flex-grow flex flex-col justify-center px-base md:px-lg py-xl max-w-md mx-auto w-full">
<!-- Brand Lockup -->
<div class="mb-xl flex flex-col items-center">
<img alt="Ceres Trade Logo" class="h-16 w-auto mb-md object-contain" src="{{ asset('img/logo.png') }}">
<h2 class="font-headline-lg text-headline-lg text-primary">Bienvenido de nuevo</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Gestión  de suministros de trigo. </p>
</div>



<form method="POST" action="{{ route('login') }}" class="space-y-md" >
                @csrf
                <!-- Email Input -->
                <div>
                    <x-forms.input label="Correo" name="email" type="email" placeholder="your@email.com" autofocus />
                </div>

                <!-- Password Input -->
                <div>
                    <x-forms.input label="Contraseña" name="password" type="password" placeholder="••••••••" />

                    <!-- Remember me & password reset -->
                    <div class="flex items-center justify-between mt-2">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-xs text-blue-600 dark:text-blue-400 hover:underline">{{ __('Forgot password?') }}</a>
                        @endif
                        <x-forms.checkbox label="Recordar contraseña" name="remember" />
                    </div>
                </div>

                <!-- Login Button -->
                <x-button type="primary" class="w-full">{{ __('Iniciar Sesion ') }}</x-button>
            </form>

            @if (Route::has('register'))
                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Don\'t have an account?') }}
                        <a href="{{ route('register') }}"
                            class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ __('Sign up') }}</a>
                    </p>
                </div>
            @endif

<script>
        // Micro-interaction for the login button
        document.querySelector('form').addEventListener('submit', (e) => {
            const btn = e.target.querySelector('button');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Validando...';
            btn.disabled = true;

            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }, 1500);
        });
    </script>


           </body> </html>
