from django.db import connections


def get_composition_str(po):
    """
    For a given PO (7-char), query trebovanje for WC02A/M/P materials,
    trim each to 11 chars as fabric code, then look up fabric+composition
    in settings_db, deduplicate by composition, return formatted string.
    """
    fabric_codes = []
    try:
        with connections['trebovanje_db'].cursor() as cursor:
            cursor.execute("""
                SELECT material
                FROM trebovanje.dbo.sap_coois_all
                WHERE po LIKE %s
                  AND wc IN ('WC02A', 'WC02M', 'WC02P')
                ORDER BY wc ASC
            """, ['%' + po])
            rows = cursor.fetchall()
        seen = set()
        for row in rows:
            code = (row[0] or '').strip()[:11].strip()
            if code and code not in seen:
                seen.add(code)
                fabric_codes.append(code)
    except Exception:
        return None

    if not fabric_codes:
        return None

    composition_parts = []
    seen_compositions = set()
    try:
        with connections['settings_db'].cursor() as cursor:
            for code in fabric_codes:
                cursor.execute(
                    "SELECT TOP 1 fabric, composition FROM settings.dbo.fabrics WHERE fabric = %s",
                    [code]
                )
                row = cursor.fetchone()
                if row:
                    fabric, composition = row[0], row[1]
                    if composition and composition not in seen_compositions:
                        seen_compositions.add(composition)
                        composition_parts.append(f"{fabric}-{composition}")
    except Exception:
        return None

    return '   '.join(composition_parts) if composition_parts else None
