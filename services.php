<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <!-- Hero Section -->
        <section class="heroSection servicesHero">
            <div class="container">
                <div class="heroContent">
                    <h1 class="heroTitle">Our Services</h1>
                    <p class="heroSubtitle">Comprehensive Technology Solutions for Your Business</p>
                </div>
            </div>
        </section>

        <!-- Services Overview -->
        <section class="servicesOverview">
            <div class="container">
                <div class="sectionHeader">
                    <h2 class="sectionTitle">What We Offer</h2>
                    <div class="sectionDivider"></div>
                    <p class="sectionSubtitle">From development to support, we've got you covered</p>
                </div>
            </div>
        </section>

        <!-- Service Categories -->
        <section class="serviceCategories">
            <div class="container">
                <div class="categoryTabs" id="serviceTabs">
                    <button class="categoryTab active" data-category="development">Development</button>
                    <button class="categoryTab" data-category="design">Design</button>
                    <button class="categoryTab" data-category="support">Support</button>
                    <button class="categoryTab" data-category="consulting">Consulting</button>
                </div>
            </div>
        </section>

        <!-- Services Grid -->
        <!-- Services Grid -->
<section class="servicesGrid">
    <div class="container">
        <!-- Development Services -->
        <div class="servicesContainer active" id="development-services">
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>Web Development</h3>
                <p>Custom websites built with the latest technologies to ensure speed, security, and scalability.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile App Development</h3>
                <p>Native and cross-platform mobile applications for iOS and Android devices.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>E-commerce Solutions</h3>
                <p>Complete online store setup with secure payment gateways and inventory management.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Design Services -->
        <div class="servicesContainer" id="design-services" style="display: none;">
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h3>UI/UX Design</h3>
                <p>Intuitive and beautiful user interfaces that enhance user experience and engagement.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Brand Identity</h3>
                <p>Complete branding solutions including logo design, color schemes, and brand guidelines.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-photo-video"></i>
                </div>
                <h3>Graphic Design</h3>
                <p>Eye-catching graphics for digital and print media that communicate your message effectively.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Support Services -->
        <div class="servicesContainer" id="support-services" style="display: none;">
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Technical Support</h3>
                <p>Round-the-clock assistance for all your technical issues and inquiries.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-tools"></i>
                </div>
                <h3>Maintenance</h3>
                <p>Regular updates and maintenance to keep your systems running smoothly.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Security Solutions</h3>
                <p>Comprehensive security measures to protect your digital assets.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Consulting Services -->
        <div class="servicesContainer" id="consulting-services" style="display: none;">
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>IT Strategy</h3>
                <p>Expert guidance to align your technology with business objectives.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-cloud"></i>
                </div>
                <h3>Cloud Solutions</h3>
                <p>Migration and optimization strategies for cloud infrastructure.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="serviceCard">
                <div class="serviceIcon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Digital Transformation</h3>
                <p>End-to-end digital transformation services for your business.</p>
                <a href="#" class="learnMore">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

        <!-- How It Works -->
        <section class="howItWorks">
            <div class="container">
                <div class="sectionHeader">
                    <h2 class="sectionTitle">How It Works</h2>
                    <div class="sectionDivider"></div>
                </div>
                <div class="stepsContainer">
                    <div class="step">
                        <div class="stepNumber">1</div>
                        <h3>Consultation</h3>
                        <p>We discuss your project requirements and goals</p>
                    </div>
                    <div class="step">
                        <div class="stepNumber">2</div>
                        <h3>Planning</h3>
                        <p>We create a detailed project plan and timeline</p>
                    </div>
                    <div class="step">
                        <div class="stepNumber">3</div>
                        <h3>Development</h3>
                        <p>Our team brings your project to life</p>
                    </div>
                    <div class="step">
                        <div class="stepNumber">4</div>
                        <h3>Delivery</h3>
                        <p>We deliver and deploy the final product</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="ctaSection">
            <div class="container">
                <h2>Ready to Start Your Project?</h2>
                <p>Get in touch with our team to discuss how we can help you achieve your goals.</p>
                <a href="contact.php" class="btn btnPrimary">Get Started</a>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/services.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all tab buttons and service containers
    const tabButtons = document.querySelectorAll('.categoryTab');
    const serviceContainers = document.querySelectorAll('.servicesContainer');

    // Add click event to each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.getAttribute('data-category');
            
            // Remove active class from all buttons and hide all containers
            tabButtons.forEach(btn => btn.classList.remove('active'));
            serviceContainers.forEach(container => {
                container.style.display = 'none';
                container.classList.remove('active');
            });
            
            // Add active class to clicked button and show corresponding container
            button.classList.add('active');
            const activeContainer = document.getElementById(`${category}-services`);
            if (activeContainer) {
                activeContainer.style.display = 'grid';
                activeContainer.classList.add('active');
            }
        });
    });

    // Initialize first tab as active
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }
});
</script>
</body>
</html>