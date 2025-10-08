const initBreadCrumbs = function () {
    const cont = document.querySelector(".page-nav");
    if (!cont) {
        return;
    }
    cont.scrollLeft = cont.scrollWidth;
}
initBreadCrumbs();




const initCounter = function () {
    const counter = function (e) {
        const btn =  e.target.closest(".quantity__btn");
        if (!btn) {return}
        e.preventDefault();
        const input = btn.closest(".quantity").querySelector("input");
        if (btn.classList.contains("_plus")) {
            input.value = +input.value + 1;
            input.max && input.value > input.max ? input.value = input.max : true;
        }
        else {
            input.value = +input.value - 1;
            input.min && input.value < input.min ? input.value = input.min : true;
            input.value < 0 ? input.value = 0 : true;
        }
    };
    document.addEventListener("click", counter);
};
initCounter();
const initFillSelect = function () {
    if (!document.querySelector(".select._range") && !document.querySelector(".select._multi").length) {
        return;
    }
    const fillSelect = function (e) {
        const input = e.target.closest(".select._range input") || e.target.closest(".select._multi input");
        if (!input) {
            return;
        }
        const cont = input.closest(".select");
        if (cont.classList.contains("_range")) {
            const min = cont.querySelector(".irs-hidden-input").dataset.min,
                max = cont.querySelector(".irs-hidden-input").dataset.max,
                curMin = cont.querySelector("._range1").value,
                curMax = cont.querySelector("._range2").value;
            if (curMin == min && curMax == max) {
                cont.classList.remove("_filled");
            }
            else {
                cont.classList.add("_filled");
            }
        }
        else if (cont.classList.contains("_multi")) {
            if (cont.querySelector(".checkbox__input:checked")) {
                cont.classList.add("_filled");
            }
            else {
                cont.classList.remove("_filled");
            }
        }
    },
    clearRange = function (e) {
        const btn = e.target.closest(".select._range .filter-alt__clear") || e.target.closest(".select._multi .filter-alt__clear");
        if (!btn) {
            return;
        }
        e.preventDefault();
        const cont = btn.closest(".select");
        cont.click();
        if (cont.classList.contains("_range")) {
            const min = cont.querySelector(".irs-hidden-input").dataset.min,
                max = cont.querySelector(".irs-hidden-input").dataset.max,
                curMin = cont.querySelector("._range1"),
                curMax = cont.querySelector("._range2");
            curMin.value = min;
            curMax.value = max;
            curMin.dispatchEvent(new Event('change',{bubbles: true, cancelable: false, composed: false}));
            curMax.dispatchEvent(new Event('change',{bubbles: true, cancelable: false, composed: false}));
            cont.classList.remove("_filled");
        }
        else if (cont.classList.contains("_multi")) {
            cont.querySelectorAll("input:checked").forEach(function (el) {
                el.checked = false;
                cont.classList.remove("_filled");
            })
        }
    }
    document.addEventListener("input", fillSelect);
    document.addEventListener("click", clearRange);
}
initFillSelect();
const initFilterItems = function () {
    if (!document.querySelector("[data-filters]")) {
        return;
    }
    const filterItems = function (e) {
        const btn = e.target.closest("[data-filter-btn]");
        if (!btn) {
            return;
        }
        const name = btn.dataset.filterBtn,
            cont = btn.closest("[data-filters]"),
            items = cont.querySelectorAll("[data-filter-item]"),
            sliders = cont.querySelectorAll(".swiper");
            items.forEach(function (item) {
                if (item.dataset.filterItem == name || name == "all") {
                    item.style.display = "";
                }
                else {
                    item.style.display = "none";
                }
            })
            if (sliders.length > 0) {
                sliders.forEach(function (item) {
                    item.swiper?.slideTo(0);
                    item.swiper?.update();
                })
            }
    },
    loadFilter = function () {
        const filter = document.querySelectorAll("[data-filters]");
        filter.forEach(function (item) {
            item.querySelector("[data-filter-btn]").closest("label").click();
        })
    }
    document.addEventListener("change", filterItems);
    loadFilter();
}
initFilterItems();
const initAltFilter = function() {
    const filter = document.querySelector(".filter-alt");
    if (!filter) {
        return;
    }
    const toggleDesktop = function (e) {
        const btn = e.target.closest(".filter-alt__more");
        if (!btn) {
            return;
        }
        e.preventDefault();
        filter.classList.toggle("_active");
    }
    document.addEventListener("click", toggleDesktop);
}
initAltFilter();
const initMegamenuCorrection = function () {
    const openSubmenu = function (e) {
        const btn = e.target.closest("a._dropdown");
        if (!btn || !btn.closest(".megamenu")) {
            return;
        }
        const supText = btn.querySelector('sup')?.innerText || '',
            text = btn.innerText.replace(supText, '').trim();
        btn.closest("[class*='megamenu__item-']").querySelector("[class*='megamenu__lvl-']").querySelector(".megamenu__cat").innerText = text;
    },
    closeMenu = function (e) {
        const btn = e.target.closest(".megamenu__btn");
        if (!btn) {
            return;
        }
        const cont = btn.closest(".megamenu__dropdown");
        cont.querySelector(".megamenu__item-1._active")?.classList.remove("_active");
        cont.querySelector(".megamenu__item-2._active")?.classList.remove("_active");
    }
    document.addEventListener("click", openSubmenu);
    document.addEventListener("click", closeMenu);
}

initMegamenuCorrection();
const initPickerImgs = function () {
    const picker = document.getElementById("picker");
    if (!picker) {
        return;
    }
    const pickerImgs = function (e) {
        const btn = e.target.closest("[data-picker]");
        if (!btn) {
            return;
        }
        const name = btn.dataset.picker;
        picker.querySelector("img._active").classList.remove("_active");
        picker.querySelector(`img[src*="${name}"]`).classList.add("_active");
    }
    document.addEventListener("click", pickerImgs);
}
initPickerImgs();
const initClearInput = function () {
    const inputs = document.querySelectorAll(".input-interval__value input");
    if (inputs.length < 1) {
        return;
    }
    inputs.forEach(function (item) {
        if (item.value == item.min || item.value == item.max) {
            item.value = ""
        }
    })
}
const initRanges = function () {
    const ranges = document.querySelectorAll(".range-double");
    ranges.forEach(function (item) {
        let $range = $(item.querySelector(".input-range")),
            min = +item.querySelector(".input-range").dataset.min,
            max = +item.querySelector(".input-range").dataset.max;
        if (item.querySelector("._range1")) {
            let $inputFrom = $(item.querySelector("._range1")),
                $inputTo = $(item.querySelector("._range2")),
                instance,
                from = min,
                to = max;
            $range.ionRangeSlider({
                type: "double",
                min: min,
                max: max,
                hide_min_max: true,
                hide_from_to: true,
                force_edges: true,
                onStart: updateInputs,
                onChange: updateInputs,
                onFinish: updateInputs1
            });
            instance = $range.data("ionRangeSlider");
            function updateInputs (data) {
                from = data.from;
                to = data.to;
                $inputFrom.prop("value", from);
                $inputTo.prop("value", to);
            }
            function updateInputs1 (data) {
                from = data.from;
                to = data.to;
                $inputFrom.prop("value", from);
                $inputTo.prop("value", to);
                item.querySelector("._range1").dispatchEvent(new Event('input',{bubbles: true, cancelable: false, composed: false}));
            }
            $inputFrom.on("change", function () {
                const to = $range.ionRangeSlider().data("ionRangeSlider").old_to;
                var val = $(this).prop("value");
                // validate
                if (val < min) {
                    val = min;
                } else if (val > to) {
                    val = to;
                }
                instance.update({
                    from: val
                });
                $(this).prop("value", val);
            });
            $inputTo.on("change", function () {
                const from = $range.ionRangeSlider().data("ionRangeSlider").old_from;
                var val = $(this).prop("value");
                // validate
                if (val < from) {
                    val = from;
                } else if (val > max) {
                    val = max;
                }
                instance.update({
                    to: val
                });
                $(this).prop("value", val);
            });
        }
        $range.ionRangeSlider({
            type: "double",
            hide_min_max: true,
            force_edges: true
        });
    })
    initClearInput();
}
initRanges();

const visibleElements = new Map(),
initScrollWatcher = function () {
    if (! document.querySelector(".scrollwatch") || window.innerWidth <= 767) {
        return;
    }
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                visibleElements.set(entry.target, entry.intersectionRatio);
                // if (entry.intersectionRatio > 0.5) {
                //     entry.target.classList.add("_50visible")
                // }
            } else {
                visibleElements.delete(entry.target);
                setTimeout(function () {
                    entry.target.classList.remove("_active");
                }, 500)
            }
        });
        let maxVisibleElement = null;
        let maxRatio = 0;
        let minTop = 60000;
        visibleElements.forEach((ratio, element) => {
            const top = element.getBoundingClientRect().top;
            // if (ratio > maxRatio) {
            //     maxRatio = ratio;
            //     maxVisibleElement = element;
            // }
            if (top < minTop && top > 0) {
                minTop = top;
                maxVisibleElement = element;
            }
        });
        const activeEl = document.querySelector(".scrollwatch._active")
        if (maxVisibleElement) {
            if (activeEl) {
                activeEl.classList.remove("_active");
                const anchorLink = document.querySelector(`.anchorlink[href="#${activeEl.id}"]`);
                anchorLink? anchorLink.classList.remove("_active") : true;
            }
            maxVisibleElement.classList.add("_active");
            // maxVisibleElement.classList.add("_init");
            const anchorLink = document.querySelector(`.anchorlink[href="#${maxVisibleElement.id}"]`);
            anchorLink ? anchorLink.classList.add("_active") : true;
        }
    }, { threshold: Array.from({ length: 21 }, (_, i) => i * 0.05) })
    document.querySelectorAll('.scrollwatch').forEach(el => observer.observe(el));
},
initScrollToAnchor = function () {
    if (!document.querySelector(".anchorlink")) {
        return;
    }
    const scrollToAnchor = function (e) {
        const btn = e.target.closest('.anchorlink');
        if (!btn) {
            return;
        }
        e.preventDefault();
        const id = btn.href.split('#')[1],
            el = document.getElementById(id),
            rect = el.getBoundingClientRect(),
            targetPosition = window.scrollY + rect.top - 80;
        window.scrollTo({
            top: targetPosition,
        });
    }
    document.addEventListener("click", scrollToAnchor);
}

initScrollWatcher();
initScrollToAnchor();
const initOtherSliders = function () {
    const sliders = document.querySelectorAll(".swiper._single");
    if (sliders.length > 0) {
        sliders.forEach(function (item) {
            const cont = item.closest(".swiper-parent");
            let prev, next, pagination, autoplay, fraction;
            if (cont) {
                prev = cont.querySelector(".swiper-button-prev");
                next = cont.querySelector(".swiper-button-next");
                pagination = cont.querySelector(".swiper-pagination");
                autoplay = cont.dataset.autoplay;
                fraction = cont.dataset.fraction;
            }
            new Swiper(item, {
                slidesPerView: 1,
                spaceBetween: 0,
                navigation: {
                    nextEl: next ? next : "",
                    prevEl: prev ? prev : "",
                },
                autoplay: autoplay ? {
                    delay: +autoplay,
                } : "",
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                loop: true,
                speed: 1000,
                disableOnInteraction: false,
                pagination: {
                    el: pagination ? pagination : "",
                    type: fraction ? 'fraction' : 'bullets',
                    clickable: true,
                },
            })
        })
    }

    const sliders3cols = document.querySelectorAll(".swiper._3-col");
    if (sliders3cols.length > 0) {
        sliders3cols.forEach(function (item) {
            const cont = item.closest(".swiper-parent");
            let prev, next, pagination, autoheight, fraction, loop;
            if (cont) {
                prev = cont.querySelector(".swiper-button-prev");
                next = cont.querySelector(".swiper-button-next");
                pagination = cont.querySelector(".swiper-pagination");
                fraction = cont.dataset.fraction;
                autoheight = cont.dataset.autoheight;
                loop = cont.dataset.loop;
            }
            new Swiper(item, {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: loop ? true : false,
                navigation: {
                    nextEl: next ? next : "",
                    prevEl: prev ? prev : "",
                },
                autoHeight: autoheight ? true : false,
                speed: 1000,
                watchSlidesProgress: true,
                pagination: {
                    el: pagination ? pagination : "",
                    type: fraction ? 'fraction' : 'bullets',
                    clickable: true,
                },
                breakpoints: {
                    530: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    1020: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                }
            })
        })
    }

    const sliders4cols = document.querySelectorAll(".swiper._4-col");
    if (sliders4cols.length > 0) {
        sliders4cols.forEach(function (item) {
            const cont = item.closest(".swiper-parent");
            let prev, next, pagination, autoheight, fraction, loop;
            if (cont) {
                prev = cont.querySelector(".swiper-button-prev");
                next = cont.querySelector(".swiper-button-next");
                pagination = cont.querySelector(".swiper-pagination");
                fraction = cont.dataset.fraction;
                autoheight = cont.dataset.autoheight;
                loop = cont.dataset.loop;
            }
            new Swiper(item, {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: loop ? true : false,
                navigation: {
                    nextEl: next ? next : "",
                    prevEl: prev ? prev : "",
                },
                autoHeight: autoheight ? true : false,
                speed: 1000,
                watchSlidesProgress: true,
                pagination: {
                    el: pagination ? pagination : "",
                    type: fraction ? 'fraction' : 'bullets',
                    clickable: true,
                },
                breakpoints: {
                    767: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    1023: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    1500: {
                        slidesPerView: 4,
                        spaceBetween: 20
                    },
                },
                on: {
                    afterInit: function (e) {
                        const sliders = e.$el[0].querySelectorAll(".swiper");
                        if (sliders.length > 0) {
                            sliders.forEach(function (item) {
                                item.swiper.update();
                            })
                        }
                    }
                }
            })
        })
    }

    const sliders2cols = document.querySelectorAll(".swiper._2-col");
    if (sliders2cols.length > 0) {
        sliders2cols.forEach(function (item) {
            const cont = item.closest(".swiper-parent");
            let prev, next, pagination, autoheight, fraction, loop;
            if (cont) {
                prev = cont.querySelector(".swiper-button-prev");
                next = cont.querySelector(".swiper-button-next");
                pagination = cont.querySelector(".swiper-pagination");
                fraction = cont.dataset.fraction;
                autoheight = cont.dataset.autoheight;
                loop = cont.dataset.loop;
            }
            new Swiper(item, {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: loop ? true : false,
                navigation: {
                    nextEl: next ? next : "",
                    prevEl: prev ? prev : "",
                },
                autoHeight: autoheight ? true : false,
                speed: 1000,
                watchSlidesProgress: true,
                pagination: {
                    el: pagination ? pagination : "",
                    type: fraction ? 'fraction' : 'bullets',
                    clickable: true,
                },
                breakpoints: {
                    530: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                }
            })
        })
    }

    const slidersMob = document.querySelectorAll(".swiper._mob");
    if (slidersMob.length > 0 && window.innerWidth < 530) {
        slidersMob.forEach(function (item) {
            const cont = item.closest(".swiper-parent");
            let prev, next, pagination, autoheight, fraction, loop;
            if (cont) {
                prev = cont.querySelector(".swiper-button-prev");
                next = cont.querySelector(".swiper-button-next");
                pagination = cont.querySelector(".swiper-pagination");
                fraction = cont.dataset.fraction;
                autoheight = cont.dataset.autoheight;
                loop = cont.dataset.loop;
            }
            new Swiper(item, {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: loop ? true : false,
                navigation: {
                    nextEl: next ? next : "",
                    prevEl: prev ? prev : "",
                },
                autoHeight: autoheight ? true : false,
                speed: 1000,
                watchSlidesProgress: true,
                pagination: {
                    el: pagination ? pagination : "",
                    type: fraction ? 'fraction' : 'bullets',
                    clickable: true,
                },
            })
        })
    }
}

initOtherSliders();
const initToogelCategory = function () {
    if (!document.querySelector(".categories")) {
        return;
    }
    const toogelCategory = function (e) {
        const category = e.target.closest(".category");
        if (!category) {
            return;
        }
        category.classList.toggle("_active");
    }
    document.addEventListener("click", toogelCategory);
}
initToogelCategory();
const initVerticalTabs = function () {
    if (!document.querySelector("[data-v-tabs]")) {
        return;
    }
    const verticalTabs = function (e) {
        const btn = e.target.closest(".v-tabs__list a") || e.target.closest(".v-tabs__header");
        if (!btn) {
            return;
        }
        e.preventDefault();
        if (e.target.closest(".v-tabs__list a")) {
            const cont = btn.closest("[data-v-tabs]"),
                activeContent = cont.querySelector(".v-tabs__content._active"),
                index = Array.prototype.indexOf.call(btn.parentNode.children, btn),
                activeTab = cont.querySelector(".v-tabs__list a._active");
            activeTab ? activeTab.classList.remove("_active") : null;
            activeContent ? activeContent.classList.remove("_active") : null;
            btn.classList.add("_active");
            cont.querySelectorAll(".v-tabs__content")[index].classList.add("_active");
        }
        else {
           btn.closest(".v-tabs__content").classList.toggle("_active");
        }
    }
    document.addEventListener("click", verticalTabs);
}
initVerticalTabs();