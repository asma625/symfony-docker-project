/* ===========================
   0) Early theme (reduce FOUC)
   =========================== */
(() => {
  try {
    const KEY = "theme-preference";
    const stored = localStorage.getItem(KEY) || "system";
    const systemDark = window.matchMedia("(prefers-color-scheme: dark)")
      .matches;
    const effective =
      stored === "system" ? (systemDark ? "dark" : "light") : stored;
    document.documentElement.setAttribute("data-theme", effective);
  } catch (e) {
    /* no-op */
  }
})();

/* ===========================
   1) Helpers
   =========================== */
const qs = (s, el = document) => el.querySelector(s);
const qsa = (s, el = document) => Array.from(el.querySelectorAll(s));

/* ===========================
   2) Dropdowns / Mega menu
   =========================== */
(() => {
  const navItems = qsa(".nav-item");

  navItems.forEach((item) => {
    const link = qs(".nav-link", item);
    const panel = qs(".panel", item);
    if (!link || !panel) return;

    const open = () => {
      item.setAttribute("aria-expanded", "true");
      link.setAttribute("aria-expanded", "true");
    };
    const close = () => {
      item.setAttribute("aria-expanded", "false");
      link.setAttribute("aria-expanded", "false");
    };

    // Toggle on click
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const expanded = item.getAttribute("aria-expanded") === "true";
      navItems.forEach((i) => {
        if (i !== item) i.setAttribute("aria-expanded", "false");
      });
      item.setAttribute("aria-expanded", String(!expanded));
      link.setAttribute("aria-expanded", String(!expanded));
    });

    // Hover (desktop pointers only)
    let hoverTimer;
    item.addEventListener("mouseenter", () => {
      if (window.matchMedia("(pointer: fine)").matches) {
        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(open, 80);
      }
    });
    item.addEventListener("mouseleave", () => {
      if (window.matchMedia("(pointer: fine)").matches) {
        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(close, 120);
      }
    });

    // Close on Escape
    item.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        close();
        link.focus();
      }
    });

    // Click outside closes
    document.addEventListener("click", (e) => {
      if (!item.contains(e.target)) close();
    });
  });
})();

/* ===========================
   3) Mobile drawer
   =========================== */
(() => {
  const burger = qs(".burger");
  const drawer = qs("#mobile-drawer");
  const backdrop = qs("#drawer-backdrop");

  function openDrawer() {
    if (!burger || !drawer || !backdrop) return;
    drawer.classList.add("open");
    backdrop.classList.add("open");
    backdrop.hidden = false;
    drawer.setAttribute("aria-hidden", "false");
    burger.setAttribute("aria-expanded", "true");
    const first = drawer.querySelector("input, a, button");
    if (first) first.focus();
  }

  function closeDrawer() {
    if (!burger || !drawer || !backdrop) return;
    drawer.classList.remove("open");
    backdrop.classList.remove("open");
    drawer.setAttribute("aria-hidden", "true");
    burger.setAttribute("aria-expanded", "false");
    setTimeout(() => {
      backdrop.hidden = true;
    }, 250);
    burger.focus();
  }

  if (burger) {
    burger.addEventListener("click", () => {
      const expanded = burger.getAttribute("aria-expanded") === "true";
      expanded ? closeDrawer() : openDrawer();
    });
  }
  if (backdrop) backdrop.addEventListener("click", closeDrawer);
  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && drawer && drawer.classList.contains("open"))
      closeDrawer();
  });
})();

/* ===========================
   4) Header shadow on scroll
   =========================== */
(() => {
  const header = qs(".site-header");
  const onScroll = () => {
    if (header)
      header.style.boxShadow = window.scrollY > 6 ? "var(--shadow)" : "none";
  };
  document.addEventListener("scroll", onScroll, { passive: true });
  onScroll();
})();

/* ===========================
   5) Top-level keyboard nav
   =========================== */
(() => {
  const navLinks = qsa(".nav-link");
  console.assert(Array.isArray(navLinks), "navLinks should be an Array");
  console.assert(
    typeof navLinks.forEach === "function",
    "navLinks.forEach should exist"
  );

  navLinks.forEach((lnk, idx) => {
    lnk.addEventListener("keydown", (e) => {
      if (e.key === "ArrowRight" || e.key === "ArrowLeft") {
        e.preventDefault();
        const nextIndex =
          e.key === "ArrowRight"
            ? (idx + 1) % navLinks.length
            : (idx - 1 + navLinks.length) % navLinks.length;
        const target = navLinks[nextIndex];
        if (target) target.focus();
      }
      if (e.key === "ArrowDown") {
        const item = lnk.closest(".nav-item");
        if (item) {
          item.setAttribute("aria-expanded", "true");
          lnk.setAttribute("aria-expanded", "true");
          const first = item.querySelector(
            ".panel a, .panel button, .panel input"
          );
          if (first) first.focus();
        }
      }
    });
  });
})();

/* ===========================
   6) Theme switcher
   =========================== */
(() => {
  const KEY = "theme-preference";
  const root = document.documentElement;
  const label = document.getElementById("theme-mode-label");
  const btn = document.getElementById("theme-toggle");

  const getSystem = () =>
    window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light";
  const getStored = () => localStorage.getItem(KEY) || "system";

  const apply = (mode) => {
    const effective = mode === "system" ? getSystem() : mode;
    root.setAttribute("data-theme", effective);
    if (label) label.textContent = mode.charAt(0).toUpperCase() + mode.slice(1);
    document.dispatchEvent(
      new CustomEvent("themechange", { detail: { mode, effective } })
    );
  };
  const set = (mode) => {
    localStorage.setItem(KEY, mode);
    apply(mode);
  };

  // Init & system sync
  apply(getStored());
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", () => {
      if (getStored() === "system") apply("system");
    });

  // Cycle order: System -> Light -> Dark
  if (btn)
    btn.addEventListener("click", () => {
      const order = ["system", "light", "dark"];
      const current = getStored();
      const next = order[(order.indexOf(current) + 1) % order.length];
      set(next);
    });

  // Drawer shortcuts
  ["light", "dark", "system"].forEach((m) => {
    const el = document.getElementById(`theme-${m}`);
    if (el)
      el.addEventListener("click", (e) => {
        e.preventDefault();
        set(m);
      });
  });
})();

/* ===========================
   7) Interactive particles field
   =========================== */
(() => {
  const canvas = document.getElementById("particles");
  if (!(canvas instanceof HTMLCanvasElement)) return;

  const ctx = canvas.getContext("2d");
  let dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
  let width = 0,
    height = 0;
  let particles = [];
  const mouse = { x: -9999, y: -9999 };
  const state = { enabled: true };

  const toggleBtn = document.getElementById("particles-toggle");
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;
  if (prefersReducedMotion) state.enabled = false;

  const rand = (min, max) => Math.random() * (max - min) + min;
  const clamp = (n, a, b) => Math.max(a, Math.min(b, n));

  function readVar(name, fallback) {
    const v = getComputedStyle(document.documentElement)
      .getPropertyValue(name)
      .trim();
    return v || fallback;
  }

  function resize() {
    width = canvas.clientWidth = window.innerWidth;
    height = canvas.clientHeight = window.innerHeight;
    canvas.width = Math.floor(width * dpr);
    canvas.height = Math.floor(height * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    seed();
  }

  function seed() {
    const area = width * height;
    const density = 1 / 12000; // particles per px
    const target = clamp(Math.floor(area * density), 40, 160);
    particles = new Array(target).fill(0).map(() => ({
      x: rand(0, width),
      y: rand(0, height),
      vx: rand(-0.4, 0.4),
      vy: rand(-0.4, 0.4),
      r: rand(1.2, 2.2)
    }));
  }

  function update() {
    const linkDist = 110;
    const repelRadius = 80;
    const repelStrength = 0.08;

    for (const p of particles) {
      const dx = p.x - mouse.x;
      const dy = p.y - mouse.y;
      const d2 = dx * dx + dy * dy;

      if (d2 < repelRadius * repelRadius) {
        const d = Math.sqrt(d2) || 1;
        const ux = dx / d,
          uy = dy / d;
        p.vx += ux * repelStrength;
        p.vy += uy * repelStrength;
      }

      p.x += p.vx;
      p.y += p.vy;

      // wrap edges
      if (p.x < -10) p.x = width + 10;
      if (p.x > width + 10) p.x = -10;
      if (p.y < -10) p.y = height + 10;
      if (p.y > height + 10) p.y = -10;

      // friction
      p.vx *= 0.997;
      p.vy *= 0.997;
    }

    // draw
    ctx.clearRect(0, 0, width, height);
    const dot = readVar("--muted", "#88a");
    const line = readVar("--brand", "#59f");
    ctx.fillStyle = dot;

    // dots
    for (const p of particles) {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fill();
    }

    // connections
    ctx.strokeStyle = line;
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const a = particles[i],
          b = particles[j];
        const dx = a.x - b.x,
          dy = a.y - b.y;
        const d2 = dx * dx + dy * dy;
        if (d2 < linkDist * linkDist) {
          const alpha = 1 - Math.sqrt(d2) / linkDist;
          ctx.globalAlpha = alpha * 0.6;
          ctx.beginPath();
          ctx.moveTo(a.x, a.y);
          ctx.lineTo(b.x, b.y);
          ctx.stroke();
        }
      }
    }
    ctx.globalAlpha = 1;
  }

  let rafId = 0;
  function loop() {
    if (!state.enabled) return;
    update();
    rafId = requestAnimationFrame(loop);
  }

  // Listeners
  window.addEventListener(
    "resize",
    () => {
      dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
      resize();
    },
    { passive: true }
  );

  window.addEventListener(
    "pointermove",
    (e) => {
      mouse.x = e.clientX;
      mouse.y = e.clientY;
    },
    { passive: true }
  );
  window.addEventListener(
    "pointerleave",
    () => {
      mouse.x = -9999;
      mouse.y = -9999;
    },
    { passive: true }
  );

  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      cancelAnimationFrame(rafId);
    } else if (state.enabled) {
      loop();
    }
  });

  if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      state.enabled = !state.enabled;
      toggleBtn.setAttribute("aria-pressed", String(state.enabled));
      if (state.enabled) loop();
      else {
        cancelAnimationFrame(rafId);
        ctx.clearRect(0, 0, width, height);
      }
    });
  }

  // Re-tint on theme change
  document.addEventListener("themechange", () => {
    /* colours update automatically next frame */
  });

  // Init
  resize();
  if (state.enabled) loop();

  // Basic runtime asserts (light tests)
  console.assert(
    canvas instanceof HTMLCanvasElement,
    "Particles canvas should exist"
  );
  console.assert(particles.length > 0, "Particles should be seeded");
})();
