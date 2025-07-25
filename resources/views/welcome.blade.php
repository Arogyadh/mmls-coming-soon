<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6Lf2soMrAAAAADnZ_jJnI_TAX1h9BXf79hNHhQAA"></script>
    <style>
        /* Spinner styles */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff33;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .submit-button {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .submit-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="content-wrapper">
            <header class="header">
                <img src="/images/mmls_transparentbg.png" alt="Logo" class="logo">
            </header>

            <main class="main-content">
                <div class="content-grid">
                    <section class="text-content">
                        <div class="content-inner">
                            <h1 class="main-title">Coming soon.</h1>
                            <div class="text-section">
                                <div class="text-wrapper">
                                    <h2 class="subtitle">Full API access to the manufactured housing data and insights.</h2>
                                    <p class="description">Sign up to for news, updates and early access.</p>
                                </div>
                                <form class="email-form" id="emailForm" method="POST" action="{{ route('notify') }}">
                                    @csrf
                                    <div class="form-container">
                                        <input
                                            type="email"
                                            id="emailInput"
                                            placeholder="Email"
                                            class="email-input"
                                            name="email"
                                            required>
                                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                        <input type="hidden" name="website" id="website" value="">
                                        <button type="submit" class="submit-button" id="submitButton">
                                            <div class="spinner" id="spinner"></div>
                                            <span class="button-text">Notify Me</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <aside class="illustration-section">
                        <img src="/images/illustration.png" alt="Coming soon illustration" class="illustration">
                    </aside>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if(session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
    @endif
    @if(session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
    @endif
    <script>
        grecaptcha.ready(function() {
            $('#emailForm').on('submit', function(e) {
                e.preventDefault();
                
                // Show spinner and disable button
                const submitButton = $('#submitButton');
                const spinner = $('#spinner');
                const buttonText = $('.button-text');
                
                submitButton.prop('disabled', true);
                spinner.show();
                buttonText.text('Submitting...');
                
                grecaptcha.execute('6Lf2soMrAAAAADnZ_jJnI_TAX1h9BXf79hNHhQAA', {
                    action: 'submit'
                }).then(function(token) {
                    $('#g-recaptcha-response').val(token);
                    e.target.submit();
                }).catch(function(error) {
                    // Re-enable button if there's an error
                    submitButton.prop('disabled', false);
                    spinner.hide();
                    buttonText.text('Notify Me');
                });
            });
        });
    </script>
</body>

</html>