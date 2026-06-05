# Preparation Django Project — CLAUDE.md

## Pregled projekta

Django aplikacija za upravljanje preparacijom (priprema etiketa) u fabrici. Pokriva tri tipa etiketa: **Barcode** (zelena boja), **Carelabel** (plava boja), **RFID** (ljubičasta boja — dodat jun 2026, još u test fazi na linijama).

Tri lokacije: **SU** (Subotica — preparation app), **Kikinda** (kikinda app), **Senta** (senta app). Linije/moduli su u **line** app.

---

## Arhitektura — Django aplikacije

| App | Opis |
|-----|------|
| `core` | Modeli, base template, auth |
| `preparation` | Glavna app — SU lokacija |
| `kikinda` | Kikinda lokacija |
| `senta` | Senta lokacija |
| `line` | Linije/moduli — zahtevi od strane linije |
| `job_management` | Upravljanje poslovima štampe |

---

## Baze podataka (settings.py connections)

| Alias | Sadržaj |
|-------|---------|
| `default` | Glavna preparation baza |
| `posummary_db` | PO summary — izvor za import narudžbi |
| `inteos_db` | Inteos — BB box qty (za target_qty kalkulaciju) |
| `bbstock_db` | BB Stock — linije, lokacije |
| `trebovanje_db` | SAP COOIS — materijali, kompozicija |

---

## Core modeli (`core/models.py`)

### Tip-parovi (Barcode / Carelabel / RFID)

Svaki tip ima isti set od 4 modela:

| Model | Tabela | Opis |
|-------|--------|------|
| `BarcodeStocks` / `CarelabelStocks` / `RfidStocks` | `barcode_stocks` / `carelabel_stocks` / `rfid_stocks` | Stanje štampanih etiketa. Polja: po_id, user_id, ponum, size, qty, module, status, type, comment, machine, qty_waste (samo Barcode i RFID), created_at, updated_at |
| `BarcodeRequests` / `CarelabelRequests` / `RfidRequests` | `barcode_requests` / `carelabel_requests` / `rfid_requests` | Zahtevi za etikete. Polja: po_id, user_id, ponum, size, qty, module, leader, status, type, comment, created_at, updated_at |
| `BarcodeKIStocks` / `CarelabelKIStocks` / `RfidKIStocks` | `barcode_ki_stocks` / `carelabel_ki_stocks` / `rfid_ki_stocks` | Stanje u Kikindi. Ima i `qty_to_receive` |
| `BarcodeSEStocks` / `CarelabelSEStocks` / `RfidSEStocks` | `barcode_se_stocks` / `carelabel_se_stocks` / `rfid_se_stocks` | Stanje u Senti. Ima i `qty_to_receive` |

### `type` vrednosti u Stocks tabelama
- `new` — normalan unos add_to_stock
- `undo` — reduce_from_stock (negativan qty)
- `insert` — placeholder kreiran pri importu PO (qty=0, videti napomenu dole)
- `transfer_ki` / `transfer_se` — transfer ka Kikindi/Senti

### `type` vrednosti u Requests tabelama
- `modul` — zahtev sa linije ili back_from_module
- `preparation` — manual request
- `transfer_ki` / `transfer_se` — transfer
- `return_ki` / `return_se` — povrat iz Kikinde/Sente

### `status` vrednosti u Requests
- `pending` — čeka potvrdu (qty=0 pri kreiranju)
- `confirmed` — potvrđen sa količinom
- `done` — završen
- `error` — pogrešan/otkazan (qty se stavlja na 0)
- `back` — povrat iz modula

### Ostali modeli
- `Pos` — tabela narudžbi (tabela `pos`)
- `PrepLocations` — lokacije u SU (tabela `prep_locations`)
- `PrintRequestLabels` — print queue za Zebra printer
- `SecondQRequests` — zahtevi za second quality etikete
- `ThrowAway`, `Leftovers`, `Leftovers2` — otpad i viškovi
- `JobManagementItem` / `JobManagementItemLog` — upravljanje poslovima štampe
- `Operator` — operateri na mašinama

---

## Napomena — `type='insert'` placeholder

Kada se importuje **novi PO** (`import_pos_data`), automatski se kreira po jedna linija u `BarcodeStocks`, `CarelabelStocks` i `RfidStocks` sa `qty=0, type='insert'`. 

**Razlog:** Istorijski — da PO bude vidljiv u stock tabelama. Zapravo nije neophodno jer se koristi LEFT JOIN sa ISNULL/or 0, tako da PO bez stock zapisa dobija 0. Placeholder ne menja kalkulacije.

---

## preparation app — view funkcije

### Stock tabele
- **`po_stock`** — kompletan pregled stanja po PO. LEFT JOIN na barcode/carelabel/rfid stocks i requests. Povlači i `inteos_db` za BB box qty → `target_qty = max(total_order_qty, bb_sum)`. Kalkuliše: stock_percentage, to_print, on_stock, request za svaki tip. Sporo se učitava zbog inteos_db cross-server query-ja.
- **`po_stock_new`** — optimizovanija verzija. Ista logika kao `po_stock` uključujući inteos_db. Ima DataTables toggle dugmiće za Barcode/Carelabel/RFID kolone, "Show more details" za sporedne kolone (Color Desc, Flash, Brand, Hangtag, Num lines, Po loc), Skeda kolona je sticky/fiksna.

### Request tabele
- **`barcode_requests`** — pregled i upravljanje barcode zahtevima. Akcije: `edit`, `error`, `print`. Akcija `rfid` postoji u kodu ali je sakrivena (zakomentarisana u template-u).
- **`carelabel_requests`** — isto kao barcode_requests ali za carelabel. Akcija `rfid` sakrivena.
- **`rfid_requests`** — pregled i upravljanje RFID zahtevima. Akcije: `edit`, `error`, `print`.
- **`secondq_requests`** — second quality zahtevi.

### Funkcije (add/remove/transfer)
- **`add_to_stock`** — dodavanje etiketa na stanje. POST: barcode checkbox + machine radio (AUTOTEX/SGF/NOVEXX/NOVEXX 90deg/ZEBRA 600), carelabel checkbox + type radio (REGULAR/ON ROLL), rfid checkbox (bez type — snima machine=null). Kreira zapis u odgovarajućoj Stocks tabeli.
- **`back_from_module`** — povrat etiketa sa linije na stanje. Kreira negativni zapis u Requests (type="modul", status="back"). Podržava barcode/carelabel/rfid.
- **`reduce_from_stock`** — undo/smanjivanje stanja. Kreira negativni zapis u Stocks (type="undo"). Podržava barcode/carelabel/rfid.
- **`transfer_to_kikinda`** — transfer ka Kikindi. Proverava dovoljno stanja, kreira Requests zapis (status='done', type='transfer_ki') + KIStocks zapis (status='to_receive'). Podržava barcode/carelabel/rfid.
- **`transfer_to_senta`** — isto ka Senti. Kreira SEStocks.
- **`manual_request`** — ručni zahtev. qty=0 → status='pending', qty>0 → status='confirmed'. Podržava barcode/carelabel/rfid.

### PO upravljanje
- **`import_pos_data`** — importuje/ažurira PO iz posummary_db. Za svaki novi PO kreira placeholder u BarcodeStocks/CarelabelStocks/RfidStocks (qty=0, type='insert').
- **`close_pos_data`** — zatvara PO-eve koji su Closed ili DELETED u posummary_db. Samo UPDATE na `pos.closed_po`.
- **`pos_table`** / **`edit_pos`** — pregled i editovanje PO tabele.
- **`prep_locations`** / **`assign_location_to_po`** — upravljanje lokacijama u SU.

### Ostalo
- **`throw_away`** — evidencija otpada materijala.
- **`leftover`** / **`leftover2`** — evidencija viškova materijala. `leftover2` podržava tipove: barcode, carelabel, rfid, sticker.
- **`log_tables`** — pregled log tabela.

---

## line app — view funkcije

- **`ll_login`** — login sa username (leader ime). Prikazuje dugme "Pošalji zahtev" za kreiranje zahteva.
- **`request_for_b_c`** — forma za slanje zahteva sa linije. Podržava barcode, carelabel, RFID (RFID ima napomenu "Još u test fazi"). Duplicate check na 15 sekundi. Po submit-u — redirect na `ll_login`. Kreira zapis u odgovarajućoj Requests tabeli (type="modul", status="pending", module=username).
- **`request_for_sq`** — zahtev za second quality etikete.
- **`request_history`** — istorija zahteva za liniju. Type parametar: `b`=barcode, `c`=carelabel, `r`=rfid. Prikazuje poslednjih godinu dana.

---

## kikinda app — view funkcije

- **`po_stock`** — stock tabela za Kikinda lokaciju (KIStocks).
- **`receive_from_su_b`** — prijem barcode etiketa iz SU. Potvrđuje `qty_to_receive` u BarcodeKIStocks (status='to_receive' → 'stock').
- **`receive_from_su_c`** — isto za carelabel.
- **`give_to_the_line`** — davanje etiketa na liniju. Proverava KIStocks stanje, kreira negativni zapis (type="in_line"). Podržava barcode/carelabel/rfid.
- **`return_to_main`** — povrat etiketa u SU. Kreira negativni KIStocks + negativni Requests (type="return_ki"). Podržava barcode/carelabel/rfid.
- **`reduce_from_stock`** — smanjivanje KI stanja (type="reduce"). Podržava barcode/carelabel/rfid.
- **`back_from_module`** — povrat sa modula na KI stanje. Kreira pozitivni KIStocks (type="in_line"). Podržava barcode/carelabel/rfid.

---

## senta app — view funkcije

Identičan pattern kao kikinda ali za Senta lokaciju i SEStocks tabele:

- **`receive_from_su_b`** / **`receive_from_su_c`** — prijem iz SU
- **`give_to_the_line`** — davanje na liniju (barcode/carelabel/rfid)
- **`return_to_main`** — povrat u SU (barcode/carelabel/rfid)
- **`reduce_from_stock`** — smanjivanje SE stanja (barcode/carelabel/rfid)
- **`back_from_module`** — povrat sa modula (barcode/carelabel/rfid)

---

## job_management app — view funkcije

- **`_build_pos_list`** — helper funkcija. Prikuplja PO listu iz default+inteos+posummary+bbstock baze. Kalkuliše prioritet (1-4) za svaki tip (BARCODE/CARELABEL/RFID) na osnovu: qty_to_cut + bb_su_delta + target_qty. Uključuje rfid_stocks JOIN i r_delta/r_priority kalkulacije.
- **`pos_overview`** — pregled PO-eva sa prioritetima.
- **`apply_jobs`** — regeneriše job listu. Briše sve NEW stavke, re-evaluira svaki PO za tipove `['CARELABEL', 'BARCODE', 'RFID']`. Preskače ako priority='Nothing'.
- **`job_items`** — lista trenutnih jobova sortirana po prioritetu.
- **`assign_operator`** — dodela operatera jobu (AJAX). Menja status u 'ASSIGNED'.
- **`send_to_stock`** — završava job. Kreira Stocks zapis (BarcodeStocks/CarelabelStocks/RfidStocks), kreira JobManagementItemLog, briše job.
- **`print_job_item`** — kreira PrintRequestLabels zapis za Zebra printer.
- **`operator_add`** / **`operator_edit`** / **`operators_list`** — upravljanje operaterima.
- **`job_logs`** — pregled loga završenih jobova.

---

## Tok podataka — primer RFID etiketa

```
1. import_pos_data → pos tabela + rfid_stocks placeholder (qty=0, type='insert')
2. preparation/add_to_stock → rfid_stocks (qty=X, type='new')
3. preparation/transfer_to_kikinda → rfid_requests (type='transfer_ki') + rfid_ki_stocks (status='to_receive')
4. kikinda/receive_from_su_r → rfid_ki_stocks (status='stock') [NIJE JOŠ IMPLEMENTIRANO]
5. kikinda/give_to_the_line → rfid_ki_stocks negativni zapis (type='in_line')
6. kikinda/back_from_module → rfid_ki_stocks pozitivni povrat
7. kikinda/return_to_main → rfid_ki_stocks negativni + rfid_requests negativni (type='return_ki')
```

---

## RFID napomene (jun 2026)

- RFID je dodat kao treći tip pored Barcode i Carelabel
- Boja: `#bf7fff` (svetlo ljubičasta) u nav baru, `mediumpurple` u stock tabeli headerima
- Na linijama (`request_for_b_c.html`) ima napomenu "Još u test fazi"
- `machine` polje za RFID se snima kao `null` (nema tip selektora)
- `receive_from_su_r` view za prijem RFID u kikindi/senti **nije implementiran** — samo barcode (`_b`) i carelabel (`_c`) imaju receive view
- Akcija `rfid` (dugme "Confirm request for RFID labels") na barcode/carelabel requests je sakrivena u template-u ali kod ostaje — radila je: `status='done', qty=0`

---

## Placeholder logika — `type='insert'`

Pri importu novog PO (`import_pos_data`) kreira se `qty=0` zapis u BarcodeStocks, CarelabelStocks, RfidStocks.

**Nije funkcionalno neophodan** — sve stock tabele se koriste kroz LEFT JOIN sa ISNULL/or 0, pa PO bez stock zapisa dobija 0 vrednosti. Placeholder je istorijski artefakt, ostavljen da se ne menja postojeće ponašanje.

---

## Boje po tipu

| Tip | Nav link boja | Header pozadina |
|-----|---------------|-----------------|
| Barcode | `darkseagreen` | `darkseagreen` |
| Carelabel | `deepskyblue` | `deepskyblue` |
| RFID | `#bf7fff` | `mediumpurple` |
| SecondQ | `orange` | — |
