<div class="footer-links">
    <div class="footer-links-column">
        <div class="social-newsletter-signup">
            <h3>Newsletter Signup</h3>
            {{ Form::open(array('route' => 'register.store')) }}

            {{ Former::text('email')->placeholder('Enter email address')->label('') }}

            <button class="btn btn-orange"><i class="fa fa-envelope"></i></button>

            {{ Form::close() }}
        </div>
        <div class="social-links">
            <p>Follow us on:</p>
            <ul>
                <li><a href="http://www.twitter.com/">{{ HTML::image('assets/images/social/twitter.png', 'Follow us on Twitter') }}</a></li>
                <li><a href="http://www.facebook.com/">{{ HTML::image('assets/images/social/facebook.png', 'Follow us on Facebook') }}</a></li>
                <li><a href="http://www.googleplus.com/">{{ HTML::image('assets/images/social/googleplus.png', 'Follow us on Google Plus') }}</a></li>
                <li><a href="http://www.linkedin.com/">{{ HTML::image('assets/images/social/linkedin.png', 'Follow us on Linked In') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-links-column">
        <ul>
            <li><h3>Company</h3></li>
            <li><a href="">About us</a></li>
            <li><a href="">How it works</a></li>
            <li><a href="">Testimonials</a></li>
            <li><a href="">Press</a></li>
            <li><a href="">Jobs</a></li>
        </ul>
    </div>
    <div class="footer-links-column">
        <ul>
            <li><h3>Support</h3></li>
            <li><a href="">Help</a></li>
            <li><a href="">Privacy</a></li>
            <li><a href="">Contact Us</a></li>
            <li><a href="">Terms of Use</a></li>
            <li><a href="">Sitemap</a></li>
        </ul>
    </div>
</div>

<div class="footer-copyright">
    <p>&copy;2015 Smooth Move. All rights reserved.</p>
</div>
