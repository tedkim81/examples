# examples

## Overview

This repository contains example projects and code samples. The current example demonstrates a simple PHP URL shortener with separate endpoints for creating short URLs and redirecting short URL slugs.

---

## Repository Structure

```bash
.
├── nextjs-sample/          # Next.js Hello World sample
└── url_shortener/          # PHP URL shortener example
    ├── redirect/           # Redirect endpoint that resolves a slug to a destination URL
    └── www/                # API endpoint for creating shortened URLs
```

TODO: Add detailed README files inside each example directory with setup, usage, and implementation notes.

---

## Getting Started

```bash
# Clone the repository
git clone https://github.com/tedkim81/examples.git
cd examples
```

Refer to each example directory for specific usage and setup instructions.

---

## Next.js Sample

The `nextjs-sample/` directory contains a minimal Next.js sample project. It provides a sample page that renders "Hello World" at the root route (`/`).

To run the sample locally:

```bash
cd nextjs-sample
npm install
npm run dev
```

For production builds and startup commands, see the `nextjs-sample/README.md` file.

---

## Contributing

- When adding a new example, create a dedicated folder and include a short description, such as a README or comments.
- Keep the examples simple and focused on demonstrating a single concept or pattern.
