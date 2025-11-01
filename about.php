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
    <title>About Us - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <!-- Hero Section -->
        <section class="heroSection aboutHero">
            <div class="container">
                <div class="heroContent">
                    <h1 class="heroTitle">About Tech-Hub</h1>
                    <p class="heroSubtitle">Your Trusted Partner in Technology Solutions</p>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="aboutSection">
            <div class="container">
                <div class="sectionHeader">
                    <h2 class="sectionTitle">Our Story</h2>
                    <div class="sectionDivider"></div>
                </div>
                
                <div class="aboutContent">
                    <div class="aboutImage" style="max-width: 500px; margin: 0 auto 30px;">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c" alt="Our Team" style="width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    </div>
                    <div class="aboutText">
                        <h3>Who We Are</h3>
                        <p>Founded in 2023, Tech-Hub has quickly become a leading destination for technology enthusiasts and professionals alike. What started as a small team of tech enthusiasts has grown into a thriving community of experts dedicated to bringing you the latest and greatest in technology.</p>
                        
                        <h3>Our Mission</h3>
                        <p>Our mission is to provide cutting-edge technology solutions, exceptional customer service, and expert advice to help our customers stay ahead in today's fast-paced digital world. We believe in making technology accessible and understandable for everyone.</p>
                        
                        <h3>Why Choose Us</h3>
                        <div class="featuresGrid">
                            <div class="featureItem">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Quality Products</h4>
                                <p>We offer only the highest quality products from trusted brands.</p>
                            </div>
                            <div class="featureItem">
                                <i class="fas fa-headset"></i>
                                <h4>24/7 Support</h4>
                                <p>Our expert team is always here to help with any questions or issues.</p>
                            </div>
                            <div class="featureItem">
                                <i class="fas fa-truck"></i>
                                <h4>Fast Shipping</h4>
                                <p>Quick and reliable delivery to your doorstep.</p>
                            </div>
                            <div class="featureItem">
                                <i class="fas fa-undo"></i>
                                <h4>Easy Returns</h4>
                                <p>Hassle-free returns within 30 days of purchase.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="teamSection">
            <div class="container">
                <div class="sectionHeader">
                    <h2 class="sectionTitle">Meet Our Team</h2>
                    <div class="sectionDivider"></div>
                    <p class="sectionSubtitle">The brilliant minds behind Tech-Hub</p>
                </div>
                
                <div class="teamGrid">
                    <div class="teamMember">
                        <div class="memberImage">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Team Member">
                            <div class="socialLinks">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                        <h3>John Doe</h3>
                        <p class="position">CEO & Founder</p>
                    </div>
                    
                    <div class="teamMember">
                        <div class="memberImage">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Team Member">
                            <div class="socialLinks">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                        <h3>Jane Smith</h3>
                        <p class="position">CTO</p>
                    </div>
                    
                    <div class="teamMember">
                        <div class="memberImage">
                            <img src="https://randomuser.me/api/portraits/men/68.jpg" alt="Team Member">
                            <div class="socialLinks">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                        <h3>Mike Johnson</h3>
                        <p class="position">Lead Developer</p>
                    </div>
                    
                    <div class="teamMember">
                        <div class="memberImage">
                            <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Team Member">
                            <div class="socialLinks">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                        <h3>Sarah Williams</h3>
                        <p class="position">Customer Support Lead</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonialsSection">
            <div class="container">
                <div class="sectionHeader">
                    <h2 class="sectionTitle">What Our Customers Say</h2>
                    <div class="sectionDivider"></div>
                </div>
                
                <div class="testimonialsSlider">
                    <div class="testimonial">
                        <div class="testimonialContent">
                            <i class="fas fa-quote-left quoteIcon"></i>
                            <p>Tech-Hub has been my go-to for all my tech needs. Their customer service is exceptional and their product quality is unmatched.</p>
                        </div>
                        <div class="testimonialAuthor">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Customer">
                            <div class="authorInfo">
                                <h4>Robert Johnson</h4>
                                <p>Regular Customer</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial">
                        <div class="testimonialContent">
                            <i class="fas fa-quote-left quoteIcon"></i>
                            <p>I've been shopping at Tech-Hub for years. They have the best prices and their support team is always helpful when I have questions.</p>
                        </div>
                        <div class="testimonialAuthor">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer">
                            <div class="authorInfo">
                                <h4>Emily Davis</h4>
                                <p>Business Owner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="ctaSection">
            <div class="container">
                <h2>Ready to Experience the Best in Tech?</h2>
                <p>Join thousands of satisfied customers who trust Tech-Hub for their technology needs.</p>
                <a href="index.php" class="btn btnPrimary">Shop Now</a>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>