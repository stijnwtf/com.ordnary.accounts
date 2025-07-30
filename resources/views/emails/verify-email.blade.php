{{-- resources/views/emails/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address - Numblio</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .email-wrapper {
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 48px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 24px;
        }

        .logo {
            height: 48px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin: 16px 0 8px;
            position: relative;
            z-index: 2;
        }

        .header-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            position: relative;
            z-index: 2;
        }

        .content-wrapper {
            padding: 48px 32px;
        }

        .welcome-text {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            text-align: center;
        }

        .description {
            font-size: 16px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .verify-button {
            display: block;
            text-align: center;
            margin: 40px 0;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white !important;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        }

        .button-icon {
            margin-right: 8px;
            width: 20px;
            height: 20px;
        }
        .info-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .info-icon {
            width: 24px;
            height: 24px;
            margin-bottom: 12px;
            color: #0ea5e9;
        }

        .info-title {
            font-weight: 600;
            color: #0c4a6e;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .info-text {
            color: #075985;
            font-size: 14px;
            line-height: 1.6;
        }

        .url-display {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            margin: 32px 0;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            word-break: break-all;
            color: #475569;
            text-align: center;
        }

        .steps-section {
            margin: 48px 0;
        }

        .steps-title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            margin-bottom: 32px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 24px;
            padding: 20px;
            background: rgba(249, 250, 251, 0.8);
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .step:hover {
            background: rgba(243, 244, 246, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            margin-right: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
            font-size: 16px;
        }

        .step-description {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        .security-note {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 16px;
            padding: 20px;
            margin: 32px 0;
            display: flex;
            align-items: flex-start;
        }

        .security-icon {
            width: 24px;
            height: 24px;
            color: #d97706;
            margin-right: 12px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .security-content {
            flex: 1;
        }

        .security-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 4px;
        }

        .security-text {
            font-size: 14px;
            color: #a16207;
            line-height: 1.6;
        }

        .footer-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 40px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .footer-link {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #4f46e5;
        }

        .footer-text {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 50%;
            color: #4f46e5;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 640px) {
            .email-wrapper {
                padding: 20px 10px;
            }
            
            .header-section {
                padding: 32px 20px;
            }
            
            .content-wrapper {
                padding: 32px 20px;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .footer-section {
                padding: 32px 20px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 16px;
            }
            
            .step {
                padding: 16px;
            }
            
            .button {
                padding: 16px 32px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="container">
            <!-- Header Section -->
            <div class="header-section">
                <div class="logo-container">
                    <img src="{{ asset('img/dark.png') }}" alt="Numblio Logo" class="logo">
                </div>
                <h1 class="header-title">Verify Your Email</h1>
                <p class="header-subtitle">Complete your account setup in just one click</p>
            </div>

            <!-- Main Content -->
            <div class="content-wrapper">
                <div class="welcome-text">Welcome to Numblio! 🎉</div>
                <p class="description">
                    Thanks for joining us! To get started with your account and access all features, 
                    please verify your email address by clicking the button below.
                </p>

                <div class="verify-button">
                    <a href="{{ $verificationUrl }}" class="button">
                        <svg class="button-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Verify Email Address
                    </a>
                </div>

                <div class="info-card">
                    <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="info-title">Why verify your email?</div>
                    <p class="info-text">
                        Email verification helps us ensure the security of your account and enables important 
                        notifications. It only takes a few seconds and keeps your account safe.
                    </p>
                </div>

                <div class="steps-section">
                    <h3 class="steps-title">What happens next?</h3>
                    
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-title">Click the verification button</div>
                            <p class="step-description">The button above will open a secure link to verify your email address instantly.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-title">Access your account</div>
                            <p class="step-description">Once verified, you can sign in and start using all Numblio features immediately.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-title">Explore Numblio services</div>
                            <p class="step-description">Connect to all Numblio services with your single verified account.</p>
                        </div>
                    </div>
                </div>

                <div class="security-note">
                    <svg class="security-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    <div class="security-content">
                        <div class="security-title">Security Notice</div>
                        <p class="security-text">This verification link expires in 60 minutes for your security. If you didn't request this email, you can safely ignore it.</p>
                    </div>
                </div>

                <p style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 32px;">
                    Having trouble with the button? Copy and paste this link into your browser:
                </p>

                <div class="url-display">
                    {{ $verificationUrl }}
                </div>
            </div>

            <!-- Footer Section -->
            <div class="footer-section">
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Help Center</a>
                    <a href="mailto:support@numblio.com" class="footer-link">Contact Support</a>
                </div>
                
                <p class="footer-text">
                    &copy; {{ date('Y') }} Numblio. All rights reserved.<br>
                    Making digital experiences simple and secure.
                </p>
                
                <div class="social-links">
                    <a href="#" class="social-link" title="Twitter">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-link" title="LinkedIn">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-link" title="GitHub">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>