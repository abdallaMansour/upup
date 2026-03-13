<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>دخول برمز PIN - {{ $stage->name ?? 'الملف الشخصي' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pin.css') }}" />
</head>

<body>
    <!-- ambient floating dots -->
    <div class="dots" id="dots"></div>

    <form method="POST" action="{{ route('profile.pin.verify', $stage) }}" id="pin-form" onsubmit="return document.getElementById('pin-input').value.length === 6;">
        @csrf
        @if (session('error'))
            <div class="alert alert-danger mb-3" role="alert">{{ session('error') }}</div>
        @endif
        <div class="card" id="card">
            <!-- CHARACTER -->
            <div class="char-wrap">
                <div class="character">
                    <div class="char-body"></div>
                    <div class="char-head" id="head">
                        <div class="cheek l"></div>
                        <div class="cheek r"></div>
                        <div class="char-eyes" id="eyes">
                            <div class="eye blink" id="eL">
                                <div class="eyebrow"></div>
                            </div>
                            <div class="eye blink" id="eR">
                                <div class="eyebrow"></div>
                            </div>
                        </div>
                        <div class="char-mouth" id="mouth"></div>
                    </div>
                    <div class="char-hands" id="hands">
                        <div class="hand left"></div>
                        <div class="hand right"></div>
                    </div>
                </div>
            </div>

            <h2>Enter PIN</h2>
            <p class="sub">مرحبا بك، أدخل رمز PIN للوصول إلى صفحة {{ $stage->name }}</p>

            <!-- hidden real input -->
            <input type="tel" id="pin-input" name="pin" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" />

            <!-- visual boxes -->
            <div class="pin-row" id="pin-row">
                <div class="pin-box" data-i="0">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
                <div class="pin-box" data-i="1">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
                <div class="pin-box" data-i="2">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
                <div class="pin-box" data-i="3">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
                <div class="pin-box" data-i="4">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
                <div class="pin-box" data-i="5">
                    <div class="pin-dot"></div>
                    <div class="pin-cursor"></div>
                </div>
            </div>

            <button type="submit" class="btn" id="submitBtn">Confirm PIN →</button>
        </div>
    </form>

    <script>
        // ── Floating dots ────────────────────────────────────────────
        const dotsEl = document.getElementById("dots");
        for (let i = 0; i < 18; i++) {
            const d = document.createElement("div");
            d.className = "dot";
            const size = 4 + Math.random() * 8;
            d.style.cssText = `
      width:${size}px; height:${size}px;
      left:${Math.random() * 100}%;
      animation-duration:${8 + Math.random() * 12}s;
      animation-delay:${Math.random() * 10}s;
    `;
            dotsEl.appendChild(d);
        }

        // ── Character refs ──────────────────────────────────────────
        const head = document.getElementById("head");
        const eyes = document.getElementById("eyes");
        const hands = document.getElementById("hands");
        const mouth = document.getElementById("mouth");
        const eL = document.getElementById("eL");
        const eR = document.getElementById("eR");

        // ── PIN logic ────────────────────────────────────────────────
        const pinInput = document.getElementById("pin-input");
        const boxes = document.querySelectorAll(".pin-box");
        const submitBtn = document.getElementById("submitBtn");
        const card = document.getElementById("card");

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

        // click on any box → focus hidden input
        document.getElementById("pin-row").addEventListener("click", () => {
            pinInput.focus();
        });

        pinInput.addEventListener("focus", () => {
            isFocused = true;
            updateBoxes();
            // character covers eyes
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
            // character uncovers
            hands.classList.remove("cover");
            eyes.classList.remove("peek");
            eL.classList.add("blink");
            eR.classList.add("blink");
            head.style.transform = "translateX(-50%) rotate(0deg)";
            mouth.style.width = "22px";
            mouth.style.borderRadius = "0 0 14px 14px";
        });

        pinInput.addEventListener("input", (e) => {
            // only digits
            const digits = e.target.value.replace(/\D/g, "").slice(0, 6);
            pinInput.value = digits;

            // pop animation on newly filled box
            if (digits.length > pinValue.length) {
                const newIdx = digits.length - 1;
                boxes[newIdx].classList.add("pop");
                setTimeout(() => boxes[newIdx].classList.remove("pop"), 300);
            }

            pinValue = digits;
            updateBoxes();
        });

        pinInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && pinValue.length === 6) document.getElementById("pin-form").requestSubmit();
        });


        // init
        updateBoxes();
    </script>
</body>

</html>
