#!/usr/bin/env node
/**
 * Export the fallback PNG site icons from plainleaf/assets/images/brand/icon.svg.
 *
 * These are only used until a Site Icon is set in Settings > General.
 * Output (same folder): icon-512.png, icon-192.png, icon-32.png, apple-touch-icon.png (180).
 *
 * Usage: npm run icons
 */
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import fs from 'node:fs/promises';
import sharp from 'sharp';

const root = path.resolve( path.dirname( fileURLToPath( import.meta.url ) ), '..' );
const brand = path.join( root, 'plainleaf', 'assets', 'images', 'brand' );

const icon = await fs.readFile( path.join( brand, 'icon.svg' ) );
const sizes = { 'icon-512.png': 512, 'icon-192.png': 192, 'apple-touch-icon.png': 180, 'icon-32.png': 32 };
for ( const [ name, size ] of Object.entries( sizes ) ) {
	await sharp( icon, { density: 300 } ).resize( size, size ).png( { compressionLevel: 9 } ).toFile( path.join( brand, name ) );
	console.log( `Wrote ${ name }` );
}
