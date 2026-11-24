import puppeteer from 'puppeteer-core';
import fs from 'fs';
import path from 'path';

const edgePath = 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';
const outDir = path.resolve('screenshots');

if (!fs.existsSync(outDir)) {
    fs.mkdirSync(outDir, { recursive: true });
}

const viewports = {
    desktop: { width: 1280, height: 800, deviceScaleFactor: 1 },
    tab: { width: 768, height: 1024, deviceScaleFactor: 1 },
    hp: { width: 375, height: 812, deviceScaleFactor: 2, isMobile: true }
};

async function capture() {
    console.log('Launching Edge browser...');
    const browser = await puppeteer.launch({
        executablePath: edgePath,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();

    // 1. Capture Login & Register
    for (const [devName, vp] of Object.entries(viewports)) {
        await page.setViewport(vp);

        // Login
        await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `01_login_${devName}.png`), fullPage: false });
        console.log(`Saved: 01_login_${devName}.png`);

        // Register
        await page.goto('http://127.0.0.1:8000/register', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `02_register_${devName}.png`), fullPage: false });
        console.log(`Saved: 02_register_${devName}.png`);
    }

    // Login as Admin
    await page.setViewport(viewports.desktop);
    await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle2' });
    await page.type('#email', 'admin@laundry.com');
    await page.type('#password', 'password');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle2' }),
        page.click('button[type="submit"]')
    ]);
    console.log('Logged in as Admin successfully.');

    // 2. Capture CRUD Layanan
    for (const [devName, vp] of Object.entries(viewports)) {
        await page.setViewport(vp);

        // Layanan Index (Daftar Layanan)
        await page.goto('http://127.0.0.1:8000/layanan', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `03_crud_layanan_index_${devName}.png`), fullPage: false });
        console.log(`Saved: 03_crud_layanan_index_${devName}.png`);

        // Layanan Create (Tambah Layanan)
        await page.goto('http://127.0.0.1:8000/layanan/create', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `04_crud_layanan_tambah_${devName}.png`), fullPage: false });
        console.log(`Saved: 04_crud_layanan_tambah_${devName}.png`);

        // Layanan Edit (Ubah Layanan)
        await page.goto('http://127.0.0.1:8000/layanan/1/edit', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `05_crud_layanan_ubah_${devName}.png`), fullPage: false });
        console.log(`Saved: 05_crud_layanan_ubah_${devName}.png`);
    }

    // 3. Capture Order & Rekap Order
    for (const [devName, vp] of Object.entries(viewports)) {
        await page.setViewport(vp);

        // Order Form (Catat Transaksi Baru)
        await page.goto('http://127.0.0.1:8000/transaksi/create', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `06_order_baru_${devName}.png`), fullPage: false });
        console.log(`Saved: 06_order_baru_${devName}.png`);

        // Rekap Order & Laporan Keuangan
        await page.goto('http://127.0.0.1:8000/transaksi', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `07_rekap_order_${devName}.png`), fullPage: false });
        console.log(`Saved: 07_rekap_order_${devName}.png`);

        // Dashboard
        await page.goto('http://127.0.0.1:8000/dashboard', { waitUntil: 'networkidle2' });
        await page.screenshot({ path: path.join(outDir, `08_dashboard_${devName}.png`), fullPage: false });
        console.log(`Saved: 08_dashboard_${devName}.png`);
    }

    await browser.close();
    console.log('All screenshots captured successfully!');
}

capture().catch(err => {
    console.error('Error during capture:', err);
    process.exit(1);
});
