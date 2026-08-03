@if(config('analytics.enabled'))
    {{-- Google Analytics 4 --}}
    @if(config('analytics.google_analytics.enabled'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.google_analytics.measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('analytics.google_analytics.measurement_id') }}', {
                'send_page_view': true,
                'cookie_flags': 'SameSite=None;Secure'
            });

            // Custom event tracking function
            window.trackEvent = function(eventName, eventParams = {}) {
                gtag('event', eventName, eventParams);
            };
        </script>
    @endif

    {{-- Meta Pixel (Facebook Pixel) --}}
    @if(config('analytics.meta_pixel.enabled'))
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ config('analytics.meta_pixel.pixel_id') }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                 src="https://www.facebook.com/tr?id={{ config('analytics.meta_pixel.pixel_id') }}&ev=PageView&noscript=1"/>
        </noscript>
    @endif

    {{-- Twitter Pixel --}}
    @if(config('analytics.twitter_pixel.enabled'))
        <!-- Twitter conversion tracking base code -->
        <script>
            !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
            },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
            a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
            twq('config','{{ config('analytics.twitter_pixel.pixel_id') }}');
        </script>
    @endif

    {{-- Hotjar Tracking Code --}}
    @if(config('analytics.hotjar.enabled'))
        <!-- Hotjar Tracking Code -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:{{ config('analytics.hotjar.site_id') }},hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
    @endif

    {{-- Event Tracking Helper Functions --}}
    <script>
        // Global event tracking function
        window.trackAnalyticsEvent = function(eventName, eventData = {}) {
            // Google Analytics
            @if(config('analytics.google_analytics.enabled'))
                if (typeof gtag !== 'undefined') {
                    gtag('event', eventName, eventData);
                }
            @endif

            // Meta Pixel
            @if(config('analytics.meta_pixel.enabled'))
                if (typeof fbq !== 'undefined') {
                    fbq('trackCustom', eventName, eventData);
                }
            @endif

            // Twitter Pixel
            @if(config('analytics.twitter_pixel.enabled'))
                if (typeof twq !== 'undefined') {
                    twq('event', eventName, eventData);
                }
            @endif

            console.log('Analytics Event:', eventName, eventData);
        };

        // Track ad views
        window.trackAdView = function(adId, adTitle) {
            @if(config('analytics.track_events.ad_view'))
                trackAnalyticsEvent('ad_view', {
                    ad_id: adId,
                    ad_title: adTitle,
                    event_category: 'Engagement',
                    event_label: 'Ad View'
                });
            @endif
        };

        // Track ad clicks
        window.trackAdClick = function(adId, adTitle, clickType) {
            @if(config('analytics.track_events.ad_click'))
                trackAnalyticsEvent('ad_click', {
                    ad_id: adId,
                    ad_title: adTitle,
                    click_type: clickType,
                    event_category: 'Engagement',
                    event_label: 'Ad Click - ' + clickType
                });
            @endif
        };

        // Track ad contact (call, email, whatsapp)
        window.trackAdContact = function(adId, contactMethod) {
            @if(config('analytics.track_events.ad_contact'))
                trackAnalyticsEvent('ad_contact', {
                    ad_id: adId,
                    contact_method: contactMethod,
                    event_category: 'Conversion',
                    event_label: 'Contact - ' + contactMethod,
                    value: 1
                });

                // Facebook conversion
                @if(config('analytics.meta_pixel.enabled'))
                    if (typeof fbq !== 'undefined') {
                        fbq('track', 'Contact', {
                            contact_method: contactMethod
                        });
                    }
                @endif
            @endif
        };

        // Track shares
        window.trackShare = function(contentType, contentId, platform) {
            @if(config('analytics.track_events.ad_share'))
                trackAnalyticsEvent('share', {
                    content_type: contentType,
                    content_id: contentId,
                    method: platform,
                    event_category: 'Engagement',
                    event_label: 'Share - ' + platform
                });
            @endif
        };

        // Track search
        window.trackSearch = function(searchTerm, resultsCount) {
            @if(config('analytics.track_events.search'))
                trackAnalyticsEvent('search', {
                    search_term: searchTerm,
                    results_count: resultsCount,
                    event_category: 'Search',
                    event_label: searchTerm
                });
            @endif
        };
    </script>
@endif
