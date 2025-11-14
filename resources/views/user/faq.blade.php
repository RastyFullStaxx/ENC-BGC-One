<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & FAQ</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* FAQ Container */
        .faq-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1), 0px 4px 6px -4px rgba(0, 0, 0, 0.1);
            border: 0.8px solid #e2e8f0;
            width: 100%;
            max-width: 752px;
            position: relative;
        }

        .faq-content {
            padding: 24.8px;
            position: relative;
            overflow: auto;
            max-height: 90vh;
        }

        /* Header Section */
        .dialog-header {
            margin-bottom: 40px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            position: relative;
        }

        .icon-help {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .dialog-header h2 {
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 24px;
            line-height: 32px;
            color: #1c398e;
            margin: 0;
        }

        .subtitle {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: #4a5565;
            margin: 0;
        }

        /* FAQ List */
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 0.8px;
            margin-bottom: 40px;
        }

        /* FAQ Item */
        .faq-item {
            position: relative;
        }

        .faq-button {
            width: 100%;
            height: 52px;
            background: transparent;
            border: none;
            border-bottom: 0.8px solid black;
            border-radius: 6px;
            padding: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.2s;
            position: relative;
        }

        .faq-button:hover {
            background-color: #f8f9fa;
        }

        .faq-button span {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: black;
            text-align: left;
            padding-left: 0;
        }

        .faq-button .chevron {
            position: absolute;
            right: 16px;
            top: 18px;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .faq-item.active .faq-button .chevron {
            transform: rotate(180deg);
        }

        /* FAQ Answer */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 16px 0;
        }

        .faq-answer p {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: #364153;
        }

        /* Contact Section */
        .contact-section {
            background-color: rgb(239, 246, 255);
            border: 0.8px solid #bedbff;
            border-radius: 8px;
            padding: 16.8px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-section h4 {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 24px;
            color: #1c398e;
            margin: 0;
        }

        .contact-intro {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: #364153;
            margin: 0;
        }

        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .contact-list li {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: #364153;
            padding-left: 9.52px;
            position: relative;
        }

        .contact-list li::before {
            content: "•";
            position: absolute;
            left: 0;
        }

        .contact-list li strong {
            font-weight: bold;
        }

        /* Close Button */
        .close-button {
            position: absolute;
            top: 16.8px;
            right: 16.8px;
            width: 16px;
            height: 16px;
            background: transparent;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            padding: 0;
            opacity: 0.7;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-button:hover {
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .faq-content {
                padding: 16px;
            }

            .dialog-header h2 {
                font-size: 20px;
                line-height: 28px;
            }

            .faq-button span {
                font-size: 13px;
                padding-right: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="faq-container">
        <div class="faq-content">
            <!-- Header -->
            <div class="dialog-header">
                <div class="header-title">
                    <div class="icon-help">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#1C398E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13" stroke="#1C398E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 17H12.01" stroke="#1C398E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h2>Help & Frequently Asked Questions</h2>
                </div>
                <p class="subtitle">Find answers to common questions about using the ONE Services portal</p>
            </div>

            <!-- FAQ Items -->
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-button">
                        <span>How do I create an account?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>I forgot my password. What should I do?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>How do I book a meeting room?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>How do I book special facilities or infrastructure?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>How do I schedule shuttle services?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>What do booking statuses mean?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>How can I view my booking history?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>Can I cancel or modify a booking?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>What is the date format used in the system?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>Do I need to be online to access the portal?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>Is my personal information secure?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>Who should I contact for technical support?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>Can I book resources on behalf of someone else?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>How far in advance can I make a booking?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button">
                        <span>What browsers are supported?</span>
                        <svg class="chevron" width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6L8 10L12 6" stroke="#64748B" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Answer content goes here...</p>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <h4>Still need help?</h4>
                <p class="contact-intro">If you can't find the answer you're looking for, please contact:</p>
                <ul class="contact-list">
                    <li><strong>IT Support</strong> - For technical issues</li>
                    <li><strong>Facilities Team</strong> - For meeting rooms and SFI bookings</li>
                    <li><strong>Transport Team</strong> - For shuttle service inquiries</li>
                </ul>
            </div>

            <!-- Close Button -->
            <button class="close-button" aria-label="Close">
                <svg width="16" height="16" fill="none" viewBox="0 0 16 16">
                    <path d="M12 4L4 12" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4 4L12 12" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        // FAQ Accordion Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Get all FAQ items
            const faqItems = document.querySelectorAll('.faq-item');
            
            // Add click event listener to each FAQ button
            faqItems.forEach(item => {
                const button = item.querySelector('.faq-button');
                
                button.addEventListener('click', () => {
                    // Toggle active class on the clicked item
                    const isActive = item.classList.contains('active');
                    
                    // Optional: Close other open items (uncomment if you want accordion behavior)
                    // faqItems.forEach(otherItem => {
                    //     if (otherItem !== item) {
                    //         otherItem.classList.remove('active');
                    //     }
                    // });
                    
                    // Toggle the clicked item
                    if (isActive) {
                        item.classList.remove('active');
                    } else {
                        item.classList.add('active');
                    }
                });
            });
            
            // Close button functionality
            const closeButton = document.querySelector('.close-button');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    // You can customize this behavior
                    // For example, hide the container or redirect
                    const container = document.querySelector('.faq-container');
                    container.style.display = 'none';
                    
                    // Or you could do:
                    // window.history.back();
                    // window.close();
                    // Or navigate to another page
                });
            }
            
            // Keyboard accessibility
            faqItems.forEach(item => {
                const button = item.querySelector('.faq-button');
                
                button.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        button.click();
                    }
                });
            });
        });
    </script>
</body>
</html>
