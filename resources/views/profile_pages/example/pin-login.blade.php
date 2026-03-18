<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>{{ __('profile.pin_title') }} - {{ __('profile.stage_' . $stage) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pin.css') }}" />
</head>
<body>
    <div class="dots" id="dots"></div>

    <form method="POST" action="{{ route('profile.example.' . $stage) }}" id="pin-form">
        @csrf
        @if (session('error'))
            <div class="alert alert-danger mx-3 mt-3" role="alert">{{ session('error') }}</div>
        @endif
        <div class="card" id="card">
            <div class="char-wrap">
                <div class="character">
                    <div class="char-body"></div>
                    <div class="char-head" id="head">
                        <div class="cheek l"></div>
                        <div class="cheek r"></div>
                        <div class="char-eyes" id="eyes">
                            <div class="eye blink" id="eL"><div class="eyebrow"></div></div>
                            <div class="eye blink" id="eR"><div class="eyebrow"></div></div>
                        </div>
                        <div class="char-mouth" id="mouth"></div>
                    </div>
                    <div class="char-hands" id="hands">
                        <div class="hand left"></div>
                        <div class="hand right"></div>
                    </div>
                </div>
            </div>

            <h2>{{ __('profile.pin_title') }}</h2>
            <p class="sub">{{ __('profile.pin_hint') }} ({{ __('profile.stage_' . $stage) }})</p>

            <input type="tel" id="pin-input" name="pin" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" />

            <div class="pin-row" id="pin-row">
                @for ($i = 0; $i < 6; $i++)
                <div class="pin-box" data-i="{{ $i }}">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
                @endfor
            </div>

            <button type="button" class="btn" id="submitBtn">{{ __('profile.pin_confirm') }} →</button>
        </div>
    </form>

    <script>
        const dotsEl = document.getElementById("dots");
        for (let i = 0; i < 18; i++) {
            const d = document.createElement("div");
            d.className = "dot";
            const size = 4 + Math.random() * 8;
            d.style.cssText = `width:${size}px;height:${size}px;left:${Math.random()*100}%;animation-duration:${8+Math.random()*12}s;animation-delay:${Math.random()*10}s;`;
            dotsEl.appendChild(d);
        }

        const head = document.getElementById("head");
        const eyes = document.getElementById("eyes");
        const hands = document.getElementById("hands");
        const mouth = document.getElementById("mouth");
        const eL = document.getElementById("eL");
        const eR = document.getElementById("eR");
        const pinInput = document.getElementById("pin-input");
        const boxes = document.querySelectorAll(".pin-box");
        const submitBtn = document.getElementById("submitBtn");
        const card = document.getElementById("card");
        const pinForm = document.getElementById("pin-form");

        let pinValue = "";
        let isFocused = false;

        function updateBoxes() {
            boxes.forEach((box, i) => {
                const filled = i < pinValue.length;
                box.classList.toggle("filled", filled);
                box.classList.toggle("active", i === pinValue.length && isFocused);
                box.classList.remove("pop");
            });
            submitBtn.classList.toggle("ready", pinValue.length === 6);
        }

        document.getElementById("pin-row").addEventListener("click", () => pinInput.focus());

        pinInput.addEventListener("focus", () => {
            isFocused = true;
            updateBoxes();
            hands.classList.add("cover");
            eyes.classList.add("peek");
            eL.classList.remove("blink");
            eR.classList.remove("blink");
            head.style.transform = "translateX(-50%) rotate(3deg)";
            mouth.style.width = "16px";
            mouth.style.borderRadius = "0 0 8px 8px";
            mouth.style.borderBottom = "3px solid #b06030";
        });

        pinInput.addEventListener("blur", () => {
            isFocused = false;
            updateBoxes();
            hands.classList.remove("cover");
            eyes.classList.remove("peek");
            eL.classList.add("blink");
            eR.classList.add("blink");
            head.style.transform = "translateX(-50%) rotate(0deg)";
            mouth.style.width = "22px";
            mouth.style.borderRadius = "0 0 14px 14px";
        });

        pinInput.addEventListener("input", (e) => {
            const digits = e.target.value.replace(/\D/g, "").slice(0, 6);
            pinInput.value = digits;
            if (digits.length > pinValue.length) {
                const newIdx = digits.length - 1;
                boxes[newIdx].classList.add("pop");
                setTimeout(() => boxes[newIdx].classList.remove("pop"), 300);
            }
            pinValue = digits;
            updateBoxes();
        });

        pinInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && pinValue.length === 6) handleSubmit();
        });

        submitBtn.addEventListener("click", handleSubmit);

        function handleSubmit() {
            if (pinValue.length < 6) return;
            // PIN الصحيح: 123456 (من 1 إلى 6)
            if (pinValue === "123456") {
                card.classList.add("success");
                mouth.style.width = "28px";
                mouth.style.height = "14px";
                mouth.style.borderRadius = "0 0 18px 18px";
                mouth.style.borderBottom = "3px solid #b06030";
                hands.classList.remove("cover");
                eyes.classList.remove("peek");
                eL.classList.add("blink");
                eR.classList.add("blink");
                eL.style.width = "18px";
                eL.style.height = "18px";
                eR.style.width = "18px";
                eR.style.height = "18px";
                head.style.transform = "translateX(-50%) scale(1.08)";
                setTimeout(() => pinForm.submit(), 500);
            } else {
                card.classList.add("wrong");
                mouth.style.width = "20px";
                mouth.style.borderTop = "3px solid #b06030";
                mouth.style.borderBottom = "none";
                mouth.style.borderRadius = "14px 14px 0 0";
                mouth.style.top = "auto";
                setTimeout(() => {
                    card.classList.remove("wrong");
                    mouth.style.borderTop = "none";
                    mouth.style.borderBottom = "3px solid #b06030";
                    mouth.style.borderRadius = "0 0 14px 14px";
                    pinValue = "";
                    pinInput.value = "";
                    updateBoxes();
                    pinInput.focus();
                }, 600);
            }
        }

        updateBoxes();
    </script>
</body>
</html>
