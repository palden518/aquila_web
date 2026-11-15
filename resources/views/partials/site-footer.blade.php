    {{-- ============================= FOOTER ============================= --}}
    <footer class="aquila-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h4>AQUILA</h4>
                    <p>Fine dining, takeaway, and catering in Brisbane City — Italian and Australian cuisine, open 7 days.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon" aria-label="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12Z"/></svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c2.7 0 3.1 0 4.1.1 1 .1 1.7.2 2.3.5.6.2 1.1.6 1.6 1.1.5.5.8.9 1.1 1.6.3.6.4 1.3.5 2.3.1 1 .1 1.4.1 4.1s0 3.1-.1 4.1c-.1 1-.2 1.7-.5 2.3-.2.6-.6 1.1-1.1 1.6-.5.5-.9.8-1.6 1.1-.6.3-1.3.4-2.3.5-1 .1-1.4.1-4.1.1s-3.1 0-4.1-.1c-1-.1-1.7-.2-2.3-.5-.6-.2-1.1-.6-1.6-1.1-.5-.5-.8-.9-1.1-1.6-.3-.6-.4-1.3-.5-2.3C2 15.1 2 14.7 2 12s0-3.1.1-4.1c.1-1 .2-1.7.5-2.3.2-.6.6-1.1 1.1-1.6.5-.5.9-.8 1.6-1.1.6-.3 1.3-.4 2.3-.5C8.9 2 9.3 2 12 2Zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4ZM17.4 6.6a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-6">
                    <div class="footer-col-title">Explore</div>
                    <ul>
                        <li><a href="{{ route('menu') }}">Menu</a></li>
                        <li><a href="{{ route('specials') }}">Specials and packages</a></li>
                        <li><a href="{{ route('events') }}">Events and catering</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-6">
                    <div class="footer-col-title">Visit us</div>
                    <ul>
                        <li>Brisbane City, Queensland</li>
                        <li>Open 7 days</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-col-title">Legal</div>
                    <ul>
                        <li><a href="#">Privacy policy</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Allergen information</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-md-between">
                <span>{{ date('Y') }} The Aquila. All rights reserved.</span>
                <span class="text-md-end">Brisbane City, Queensland, Australia</span>
            </div>
        </div>
    </footer>
