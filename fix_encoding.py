#!/usr/bin/env python3
import sys

# Read the UTF-16 file
with open(r'c:\laragon\www\absensi-karyawan\resources\views\admin\dashboard.blade.php', 'rb') as f:
    raw_data = f.read()

# Decode from UTF-16
content = raw_data.decode('utf-16')

# Write back as UTF-8
with open(r'c:\laragon\www\absensi-karyawan\resources\views\admin\dashboard.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print('✓ File encoding fixed: UTF-16 → UTF-8')
