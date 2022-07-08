(function (window) {

    /**
     * Console Warning function
     * Source: https://github.com/simpleanalytics/scripts/blob/ed9efd1b5ff62f27e0dc395d57068ac6a0517d14/dist/latest/latest.dev.js
     * @param {String} message
     * @returns {?Function} warn
     */
     const warn = function (message) {
        if (console && console.warn) console.warn('Recolus Script: ', message);
    };

    /**
     * Looks for an element with Recolus attributes and get them.
     * Warn and fail if missing.
     */
     const script = document.querySelector('script[data-recolus-site-id]');
     if (!script) {warn('No script found.'); return;};

     const attr = script.getAttribute.bind(script);
     const siteId = attr('data-recolus-site-id');
     const hostUrl = attr('data-recolus-host-url');
     if (!siteId) {warn('No site id found.'); return;};
     if (!hostUrl) {warn('No host url found.'); return;};

     if (!document.doctype) {warn('Add DOCTYPE html for more accurate dimensions.')};

     /**
      * Get script attributes options.
      */
     const customDomain = attr('data-recolus-domain') || '';
     const customVariable = attr('data-recolus-variable') || '';

    /**
     * Declare global window variables.
     */
    var wlocation = window.location;
    var wdocument = window.document;
    var wnavigator = window.navigator;
    var whistory = window.history;
    var scrolled = 0;
    var rid = null;

    /**
     * Declare Recolus attributes.
     * @returns {Object} attributes
     */
     const recolusAttributes = {
        siteId: siteId,
        hostUrl: hostUrl,
        customDomain: customDomain,
        customVariable: customVariable,
     };

    /**
     * Construct URL to the API endpoint of Recolus.
     * @param {String} host - URL of the Recolus server host.
     * @returns {String} endpoint - URL to the API endpoint of the Recolus server host.
     */
    const endpoint = function(hostUrl) {
        const hasTrailingSlash = hostUrl.substring(-1) === '/'
        return hostUrl + (hasTrailingSlash === true ? '' : '/') + 'api/collect'
    }

    /**
     * Determines if user agent is a bot. Approach is to get most bots, assuming other bots don't run JS.
     * Source: https://stackoverflow.com/questions/20084513/detect-search-crawlers-via-javascript/20084661
     * @param {String} userAgent - User agent that should be tested.
     * @returns {Boolean} isBot
     */
    const isBot = function(userAgent) {
        const checkNav = navigator.webdriver ||
        window.__nightmare ||
        "callPhantom" in window ||
        "_phantom" in window ||
        "phantom" in window;

        const testUa = (/bot|crawler|crawl|spider|crawling/i).test(userAgent);

        return checkNav ?? testUa;
    }

    /**
     * Checks if the website is in background (e.g. user has minimzed or switched tabs).
     * @returns {boolean}
     */
    const isInBackground = function() {
        return document.visibilityState === 'hidden';
    }

    /**
     * Checks if the website is in local.
     * Source: https://beampipe.io/js/tracker.js
     * @param {String} host - URL host (window.location.hostname).
     * @returns {boolean}
     */
    const isLocal = function(host) {
        var test = /^localhost$|^127(?:\.[0-9]+){0,2}\.[0-9]+$|^(?:0*\:)*?:?0*1$/.test(location.hostname) || location.protocol === "file:";
        if (test) {warn('Run from local or file host.')};
        return test;
    }

    /**
     * Create a UUID v4.
     * Source : https://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid
     * @returns {String} uuid.
     */
    const uuid = function () {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    /**
     * Create a now date.
     * @returns {Date} now.
     */
     const now = function () {
        return Date.now;;
    }
    /**
     * Skip your own views with an URL hash.
     * Source : https://gc.zgo.at/count.js
     */
    const toggleWithHash = function () {
        if (location.hash === '#toggle-recolus') {
            if (localStorage.getItem('skiprecolus') === '1') {
                localStorage.removeItem('skiprecolus', '1')
                alert('Recolus is now ENABLED in this browser.')
            } else {
                localStorage.setItem('skiprecolus', '1')
                alert('Recolus is now DISABLED in this browser until ' + location + ' is loaded again.')
                warn('Disabled. Recolus is now DISABLED in this browser until ' + location + ' is loaded again.');
                return;
            }
        }
    }

    /**
     * Create and store rid for reuse.
     */
     var getRid = function () {
        if (rid == null) {
            rid = uuid();
            return rid;
        } else {
            return rid;
        }
    }

    /**
     * Gathers all platform-, screen- and user-related information.
     * @param {Boolean} detailed - Include personal data.
     * @returns {Object} attributes - User-related information.
     */
    const informations = function() {
        const userAgent = navigator.userAgent;
        const language = (navigator.language || navigator.userLanguage).substr(0, 2);

        var timezone;
        try { timezone = Intl.DateTimeFormat().resolvedOptions().timeZone; } catch (e) { /* Do nothing */ }

        const data = {
            type: 'page',
            siteId: recolusAttributes.siteId,
            rid: getRid(),
            url: location.href,
            host: location.host,
            hostname: location.hostname,
            protocol: location.protocol,
            path: location.pathname,
            query: location.search,
            fragment: location.hash,
            title: document.title,
            referrer: document.referrer,
            language: language,
            userAgent: userAgent,
            timezone: timezone,
            windowWidth: window.innerWidth,
            windowHeight: window.innerHeight,
            screenWidth: window.screen.width,
            screenHeight: window.screen.height,
            customDomain: recolusAttributes.customDomain,
            customVariable: recolusAttributes.customVariable,
        }

        return data;
    }

    /**
     * Sends a POST request to a specified URL.
     * Won't catch all errors as some are already logged by the browser.
     * In this case the callback won't fire.
     * @param {String} url - URL to the API endpoint of the Recolus server.
     * @param {Object} body - JSON which will be send to the server.
     */
    const send = function (url, body) {

        // console.log(body);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status !== 201) {
                warn('Conncetion error with status ' + xhr.status + ' and message: "' + JSON.parse(xhr.responseText)?.message + '"');
                warn('Conncetion error with response ' + xhr.responseText);
            }
        };
        xhr.send(JSON.stringify(body));
    }

    /**
     * Sends a beacon request to a specified URL.
     * Won't catch errors.
     * @param {String} url - URL to the API endpoint of the Recolus server.
     * @param {Object} body - JSON which will be send to the server.
     */
     const sendBeacon = function (url, data) {
        var text = JSON.stringify(data);
        navigator.sendBeacon(url, text);
     }

    /**
     * Go.
     * Check, filter and send informations.
     */
    toggleWithHash();

    if (isBot() || isInBackground() || isLocal()) {
        warn('Page count not send. Is a bot, in background or local.');
        return;
    }

    send(endpoint(recolusAttributes.hostUrl), informations())

    /**
     * Scroll.
     * Get document height.
     */
    function documentHeight() {
        let D = window.document;
        return Math.max(
            D.body.scrollHeight, D.documentElement.scrollHeight,
            D.body.offsetHeight, D.documentElement.offsetHeight,
            D.body.clientHeight, D.documentElement.clientHeight
        );
    }

    /**
     * Scroll.
     * Get current scroll position.
     */
    function positionScroll(){
        var winheight = window.innerHeight || (document.documentElement || document.body).clientHeight;
        var docheight = documentHeight();

        var scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;
        var trackLength = docheight - winheight;
        var pctScrolled = Math.floor(scrollTop / trackLength * 100);

        return pctScrolled;
    }

    /**
     * Scroll.
     * After document loaded. Calculate max scrolled position.
     */
    window.addEventListener("load", function () {
        scrolled = positionScroll();
        window.addEventListener("scroll", function () {
            if (scrolled < positionScroll()) {scrolled = positionScroll()};
        }, false);
    });

    /**
     * Scroll.
     * Calculate scroll.
     */
     var scroll = function () {
         return Math.max(0, scrolled, positionScroll());
     };

    /**
     * Duration.
     * Initializes Variables.
     * Source : https://gist.github.com/imkevinxu/4393523
     */

     var durationHidden, durationState, durationVisibilityChange,
         _this = this;

     if (document.hidden != null) {
        durationHidden = "hidden";
         durationVisibilityChange = "visibilitychange";
         durationState = "visibilityState";
     } else if (document.mozHidden != null) {
        durationHidden = "mozHidden";
         durationVisibilityChange = "mozvisibilitychange";
         durationState = "mozVisibilityState";
     } else if (document.msHidden != null) {
        durationHidden = "msHidden";
         durationVisibilityChange = "msvisibilitychange";
         durationState = "msVisibilityState";
     } else if (document.webkitHidden != null) {
        durationHidden = "webkitHidden";
         durationVisibilityChange = "webkitvisibilitychange";
         durationState = "webkitVisibilityState";
     }

     this.d = new Date();
     this.new_d = new Date();

     var totalDuration = 0;

    /**
     * Duration.
     * Calculates Time Spent on page upon leaving/closing page
     * Source : https://gist.github.com/imkevinxu/4393523
     */
     window.onunload = function () {
         _this.new_d = new Date();
         var time_spent = Math.round((_this.new_d - _this.d) / 1000);
         calculateTotalDuration(time_spent);
     };

    /**
     * Duration.
     * Calculates Time Spent on page upon unfocusing tab
     * Source : http://davidwalsh.name/page-visibility
     */
     document.addEventListener(durationVisibilityChange, (function (e) {
         if (document[durationState] === 'visible') {
             _this.d = new Date();
         } else if (document[durationHidden]) {
             _this.new_d = new Date();
             var time_spent = Math.round((_this.new_d - _this.d) / 1000);
             calculateTotalDuration(time_spent);
         }
     }), false);

     /**
     * Duration and trigger update record.
     * Calculate total duration and max scroll.
     * Prepare pdate record beacon.
     */
     var calculateTotalDuration = function (intervalTimeSpent) {
         if (intervalTimeSpent >= 1) {
             totalDuration += intervalTimeSpent;

             // console.log(totalDuration);
             // console.log('scroll : '+ scroll());

             updateRecord(getRid(), totalDuration, scroll());
         }
     }

     /**
      * Duration and scroll.
      * Update record before page leave.
      */
      var updateRecord = function (rid, duration, scroll) {

         let data = {
             rid: rid,
             duration: duration,
             scroll: scroll,
         };

         sendBeacon(endpoint(recolusAttributes.hostUrl) + '-update', data);
     };


})(window);
