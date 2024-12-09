/*global window, document*/
(function (exports) {
    'use strict';

    var extend,
        createElements,
        createCountdownElt,
        simplyCountdown;

    /**
     * Function that merge user parameters with defaults one.
     */
    extend = function (out) {
        var i,
            obj,
            key;
        out = out || {};

        for (i = 1; i < arguments.length; i += 1) {
            obj = arguments[i];

            if (obj) {
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        if (typeof obj[key] === 'object') {
                            extend(out[key], obj[key]);
                        } else {
                            out[key] = obj[key];
                        }
                    }
                }
            }
        }

        return out;
    };

    /**
     * Function to create a countdown section.
     */
    createCountdownElt = function (countdown, parameters, typeClass) {
        var sectionTag = document.createElement('div'),
            amountTag = document.createElement('span'),
            wordTag = document.createElement('span'),
            innerSectionTag = document.createElement('div');

        innerSectionTag.appendChild(amountTag);
        innerSectionTag.appendChild(wordTag);
        sectionTag.appendChild(innerSectionTag);

        sectionTag.classList.add(parameters.sectionClass, typeClass);
        amountTag.classList.add(parameters.amountClass);
        wordTag.classList.add(parameters.wordClass);

        countdown.appendChild(sectionTag);

        return { full: sectionTag, amount: amountTag, word: wordTag };
    };

    /**
     * Create full countdown DOM elements calling createCountdownElt.
     */
    createElements = function (parameters, countdown) {
        if (!parameters.inline) {
            return {
                days: createCountdownElt(countdown, parameters, 'simply-days-section'),
                hours: createCountdownElt(countdown, parameters, 'simply-hours-section'),
                minutes: createCountdownElt(countdown, parameters, 'simply-minutes-section'),
                seconds: createCountdownElt(countdown, parameters, 'simply-seconds-section')
            };
        }

        var spanTag = document.createElement('span');
        spanTag.classList.add(parameters.inlineClass);
        return spanTag;
    };

    /**
     * Function to get EST offset.
     */
    function getESTDate(date) {
        // Calculate EST offset (UTC-5 without daylight saving, UTC-4 with daylight saving)
        var offset = date.getTimezoneOffset() / 60 + 5; // Default to UTC-5
        var estDate = new Date(date.getTime() - offset * 3600 * 1000);
        return estDate;
    }

    /**
     * simplyCountdown, create and display the countdown.
     */
    simplyCountdown = function (elt, args) {
        var parameters = extend({
                year: 2026,
                month: 1,
                day: 20,
                hours: 16,
                minutes: 30,
                seconds: 0,
                words: {
                    days: 'day',
                    hours: 'hour',
                    minutes: 'minute',
                    seconds: 'second',
                    pluralLetter: 's'
                },
                plural: true,
                inline: false,
                enableUtc: false, // Disable UTC since we're using EST
                onEnd: function () {
                    return;
                },
                refresh: 1000,
                inlineClass: 'simply-countdown-inline',
                sectionClass: 'simply-section',
                amountClass: 'simply-amount',
                wordClass: 'simply-word',
                zeroPad: false
            }, args),
            interval,
            targetDate,
            now,
            secondsLeft,
            days,
            hours,
            minutes,
            seconds,
            cd = document.querySelectorAll(elt);

        var targetTmpDate = new Date(
            parameters.year,
            parameters.month - 1,
            parameters.day,
            parameters.hours,
            parameters.minutes,
            parameters.seconds
        );

        targetDate = getESTDate(targetTmpDate);

        Array.prototype.forEach.call(cd, function (countdown) {
            var fullCountDown = createElements(parameters, countdown);

            function refresh() {
                now = getESTDate(new Date());
                secondsLeft = (targetDate - now.getTime()) / 1000;

                if (secondsLeft > 0) {
                    days = parseInt(secondsLeft / 86400, 10);
                    secondsLeft %= 86400;

                    hours = parseInt(secondsLeft / 3600, 10);
                    secondsLeft %= 3600;

                    minutes = parseInt(secondsLeft / 60, 10);
                    seconds = parseInt(secondsLeft % 60, 10);
                } else {
                    days = 0;
                    hours = 0;
                    minutes = 0;
                    seconds = 0;
                    window.clearInterval(interval);
                    parameters.onEnd();
                }

                if (parameters.inline) {
                    countdown.innerHTML =
                        days + ' ' + (days === 1 ? parameters.words.days : parameters.words.days + parameters.words.pluralLetter) + ', ' +
                        hours + ' ' + (hours === 1 ? parameters.words.hours : parameters.words.hours + parameters.words.pluralLetter) + ', ' +
                        minutes + ' ' + (minutes === 1 ? parameters.words.minutes : parameters.words.minutes + parameters.words.pluralLetter) + ', ' +
                        seconds + ' ' + (seconds === 1 ? parameters.words.seconds : parameters.words.seconds + parameters.words.pluralLetter) + '.';
                } else {
                    fullCountDown.days.amount.textContent = (parameters.zeroPad && days.toString().length < 2 ? '0' : '') + days;
                    fullCountDown.days.word.textContent = days === 1 ? parameters.words.days : parameters.words.days + parameters.words.pluralLetter;

                    fullCountDown.hours.amount.textContent = (parameters.zeroPad && hours.toString().length < 2 ? '0' : '') + hours;
                    fullCountDown.hours.word.textContent = hours === 1 ? parameters.words.hours : parameters.words.hours + parameters.words.pluralLetter;

                    fullCountDown.minutes.amount.textContent = (parameters.zeroPad && minutes.toString().length < 2 ? '0' : '') + minutes;
                    fullCountDown.minutes.word.textContent = minutes === 1 ? parameters.words.minutes : parameters.words.minutes + parameters.words.pluralLetter;

                    fullCountDown.seconds.amount.textContent = (parameters.zeroPad && seconds.toString().length < 2 ? '0' : '') + seconds;
                    fullCountDown.seconds.word.textContent = seconds === 1 ? parameters.words.seconds : parameters.words.seconds + parameters.words.pluralLetter;
                }
            }

            refresh();
            interval = window.setInterval(refresh, parameters.refresh);
        });
    };

    exports.simplyCountdown = simplyCountdown;
}(window));
