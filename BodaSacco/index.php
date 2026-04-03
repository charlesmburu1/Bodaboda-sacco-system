<?php include "includes/header.php"; ?>

<main>

<!-- HERO -->
<section class="hero">
    <div class="overlay"></div>
    <div class="hero-content">
        <h1><i class="fas fa-motorcycl"></i> Empowering Bodaboda Riders</h1>
        <p>Save, Borrow, and Grow with our SACCO. Financial services designed just for you.</p>
        <div class="hero-buttons">
            <a href="register.php" class="btn-primary">Join Now</a>
            <a href="#about" class="btn-secondary-1">Learn More</a>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section class="about" id="about">
    <div class="container about-grid">
        <!-- Left Column: Image -->
        <div class="about-image">
            <img src="assets/images/rider_images.jpg" alt="Bodaboda Rider">
        </div>

        <!-- Right Column: Text & Features -->
        <div class="about-text">
            <h2>About Us</h2>
            <p>We support bodaboda riders with financial services, helping them grow and stabilize their income. Our mission is to empower riders with easy access to loans, secure savings plans, and reliable insurance.</p>

            <!-- Feature Cards -->
            <div class="about-features">
                <div class="feature-card">
                    <i class="fas fa-coins"></i>
                    <h3>Fast Loans</h3>
                    <p>Get quick and affordable loans tailored for riders.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-piggy-bank"></i>
                    <h3>Secure Savings</h3>
                    <p>Save safely and watch your funds grow over time.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Insurance</h3>
                    <p>Protect your bike and income with reliable coverage.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="services">
    <div class="container">
        <h2>Our Services</h2>

        <div class="services-grid">

            <div class="service-box">
                <div class="icon-box">
                    <i class="fas fa-coins"></i>
                </div>
                <h3>Loans</h3>
                <p>Fast and affordable loans for riders.</p>
            </div>

            <div class="service-box">
                <div class="icon-box">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <h3>Savings</h3>
                <p>Secure savings plans.</p>
            </div>

            <div class="service-box">
                <div class="icon-box">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Insurance</h3>
                <p>Protect your bike and income.</p>
            </div>

        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials" id="testimonials">
    <div class="container">
        <h2>What Our Members Say</h2>
        <!-- Swiper -->
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide testimonial-box">
                    <div class="testimonial-header">
                        <img src="assets/images/rider_images.jpg" alt="James Mwangi">
                        <i class="fas fa-quote-left quote-icon"></i>
                    </div>
                    <p>"I bought my bike through this SACCO! The loan process was smooth and quick."</p>
                    <h4>James Mwangi</h4>
                    <div class="stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide testimonial-box">
                    <div class="testimonial-header">
                        <img src="assets/images/rider_images.jpg" alt="Peter Otieno">
                        <i class="fas fa-quote-left quote-icon"></i>
                    </div>
                    <p>"Best support and easy loans. I feel more secure and motivated now."</p>
                    <h4>Peter Otieno</h4>
                    <div class="stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="swiper-slide testimonial-box">
                    <div class="testimonial-header">
                        <img src="assets/images/rider_images.jpg" alt="Mary Wanjiku">
                        <i class="fas fa-quote-left quote-icon"></i>
                    </div>
                    <p>"Their insurance coverage saved me when I had an accident. Truly reliable."</p>
                    <h4>Mary Wanjiku</h4>
                    <div class="stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide testimonial-box">
                    <div class="testimonial-header">
                        <img src="assets/images/rider_images.jpg" alt="John Kamau">
                        <i class="fas fa-quote-left quote-icon"></i>
                    </div>
                    <p>"Saving with this SACCO is easy and convenient. Highly recommended!"</p>
                    <h4>John Kamau</h4>
                    <div class="stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section class="contact" id="contact">
    <div class="container">
        <h2>Contact Us</h2>
        <p class="contact-subtext">Have questions? Reach out to us anytime. We're here to help you grow.</p>

        <div class="contact-wrapper">

            <!-- LEFT: CONTACT INFO -->
            <div class="contact-info">

                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Location</h3>
                        <p>Nairobi, Kenya</p>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@bodasacco.co.ke</p>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Phone</h3>
                        <p>+254 700 111222</p>
                    </div>
                </div>

            </div>

            <!-- RIGHT: CONTACT FORM -->
            <div class="contact-form">
                <form action="process_contact.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>

                    <div class="form-group">
                        <textarea rows="5" name="message" placeholder="Your Message" required></textarea>
                    </div>

                    <button type="submit" class="btn-primary">Send Message</button>
                </form>
            </div>

        </div>
    </div>
</section>

</main>

<?php include "includes/footer.php"; ?>