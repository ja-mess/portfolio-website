<footer class="wb-minimal-footer">
            <div class="footer-divider"></div>
            
            <div class="quote-container">
                <i class="fas fa-quote-left quote-icon"></i>
                <span id="dynamic-quote">Empowering vision through innovation.</span>
            </div>

            <div class="footer-bottom">
                <div class="system-status">
                    <span class="status-dot"></span> 
                    <span class="status-text">Encrypted System Active</span>
                </div>
                
                <div class="copyright-text">
                    &copy; <?php echo date("Y"); ?> WalkBuddy • Command Center v1.0
                </div>
            </div>
        </footer>

        <style>
            .wb-minimal-footer {
                margin-top: 40px;
                padding: 20px 0;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
                font-family: 'Poppins', sans-serif;
            }

            .footer-divider {
                width: 50px;
                height: 2px;
                background: var(--primary-yellow);
                opacity: 0.3;
                border-radius: 2px;
                margin-bottom: 10px;
            }

            .quote-container {
                display: flex;
                align-items: center;
                gap: 10px;
                color: var(--text-gray);
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                font-weight: 400;
                text-transform: uppercase;
                min-height: 20px;
            }

            .quote-icon {
                font-size: 0.6rem;
                color: var(--primary-yellow);
                opacity: 0.5;
            }

            .footer-bottom {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
            }

            .system-status {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 0.6rem;
                color: rgba(255, 255, 255, 0.3);
                letter-spacing: 2px;
                text-transform: uppercase;
            }

            .status-dot {
                width: 5px;
                height: 5px;
                background: #2ecc71;
                border-radius: 50%;
                box-shadow: 0 0 8px #2ecc71;
                animation: pulse 2s infinite;
            }

            .copyright-text {
                font-size: 0.6rem;
                color: rgba(255, 255, 255, 0.2);
                letter-spacing: 1px;
            }

            @keyframes pulse {
                0% { opacity: 0.4; }
                50% { opacity: 1; }
                100% { opacity: 0.4; }
            }

            #dynamic-quote {
                transition: opacity 0.6s ease;
            }
        </style>

        <script>
            // Quotes na tungkol sa blind accessibility at sa project niyo
            const footerQuotes = [
                "Empowering vision through innovation.",
                "Technology for a barrier-free world.",
                "Your path, guided by intelligence.",
                "Bridging the gap between sight and soul.",
                "Innovation for independent living."
            ];

            let quoteIndex = 0;
            const quoteElement = document.getElementById('dynamic-quote');

            function rotateFooterQuote() {
                if(!quoteElement) return;
                
                quoteElement.style.opacity = 0;
                
                setTimeout(() => {
                    quoteIndex = (quoteIndex + 1) % footerQuotes.length;
                    quoteElement.innerText = footerQuotes[quoteIndex];
                    quoteElement.style.opacity = 1;
                }, 600);
            }

            // Mag-rotate bawat 7 seconds
            setInterval(rotateFooterQuote, 7000);
        </script>
        </div> </body>
</html>