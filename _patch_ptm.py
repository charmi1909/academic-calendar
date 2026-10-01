from pathlib import Path

root = Path(r"C:\xampp\htdocs\academic-calendar\resources\views\ptm")

# ---- INDEX ----
p = root / "index.blade.php"
c = p.read_text(encoding="utf-8")
repls = [
    ("Academic Calendar <span>/</span> Holiday", "Academic Calendar <span>/</span> PTM"),
    ("Holiday Management", "PTM Management"),
    ("Manage school holidays, targets and notifications.", "Manage parent teacher meetings, targets and notifications."),
    ("route('holiday.export')", "route('ptm.export')"),
    ("route('holiday.create')", "route('ptm.create')"),
    ("Add Holiday", "Add PTM"),
    ("All Holidays", "All PTMs"),
    ("View and manage all configured holidays.", "View and manage all configured parent teacher meetings."),
    ("route('holiday.index')", "route('ptm.index')"),
    ("Search holidays...", "Search PTM..."),
    ("Search holidays", "Search PTM"),
    ('id="holidayTable"', 'id="ptmTable"'),
    ("<th>Holiday</th>", "        <th>PTM</th>"),
    ("@forelse($holidays as $index => $holiday)", "@forelse($ptms as $index => $ptm)"),
    ("Get Holiday For", "Get PTM For"),
    ("$holidayFor = $holiday->holiday_for ?? [];", "$ptmFor = $ptm->ptm_for ?? [];"),
    ("if (is_string($holidayFor)) {\n                                $holidayFor = json_decode(\n                                    $holidayFor,\n                                    true\n                                ) ?? [];\n                            }",
     "if (is_string($ptmFor)) {\n                                $ptmFor = json_decode(\n                                    $ptmFor,\n                                    true\n                                ) ?? [];\n                            }"),
    ("if (empty($holidayFor)) {", "if (empty($ptmFor)) {"),
    ("!empty($holiday->staff_departments);", "!empty($ptm->staff_departments);"),
    ("!empty($holiday->student_departments) ||\n                                    !empty($holiday->standards) ||\n                                    !empty($holiday->divisions);",
     "!empty($ptm->student_departments) ||\n                                    !empty($ptm->standards) ||\n                                    !empty($ptm->divisions);"),
    ("$holidayFor = ['All'];", "$ptmFor = ['All'];"),
    ("$holidayFor = ['Staff'];", "$ptmFor = ['Staff'];"),
    ("$holidayFor = ['Student'];", "$ptmFor = ['Student'];"),
    ("$holiday->staff_departments ?? [],", "$ptm->staff_departments ?? [],"),
    ("$holiday->student_departments ?? []", "$ptm->student_departments ?? []"),
    ("$holiday->start_date ??\n                                $holiday->date;", "$ptm->start_date ??\n                                $ptm->date;"),
    ("$holiday->end_date ??", "$ptm->end_date ??"),
    ("{{ $holidays->firstItem() + $index }}", "{{ $ptms->firstItem() + $index }}"),
    ("'holiday.edit',\n                                            $holiday", "'ptm.edit',\n                                            $ptm"),
    ("Edit holiday", "Edit PTM"),
    ("'holiday.destroy',\n                                            $holiday", "'ptm.destroy',\n                                            $ptm"),
    ("Delete holiday", "Delete PTM"),
    ("Delete this holiday?", "Delete this PTM?"),
    ("<!-- Holiday -->", "<!-- PTM -->"),
    ("$holiday->title,", "$ptm->title ?: '-',"),
    ("{{ $holiday->title }}", "{{ $ptm->title ?: '-' }}"),
    ("                                            Holiday", "                                            PTM"),
    ("@if(!empty($holidayFor))", "@if(!empty($ptmFor))"),
    ("@foreach($holidayFor as $target)", "@foreach($ptmFor as $target)"),
    ("@if(!empty($holiday->standards))", "@if(!empty($ptm->standards))"),
    ("$holiday->standards", "$ptm->standards"),
    ("@if(!empty($holiday->divisions))", "@if(!empty($ptm->divisions))"),
    ("$holiday->divisions", "$ptm->divisions"),
    ("@if($holiday->send_sms)", "@if($ptm->send_sms)"),
    ("No holidays yet", "No PTMs yet"),
    ("Add your first holiday", "Add your first PTM"),
    ("@if($holidays->total() > 0)", "@if($ptms->total() > 0)"),
    ("{{ $holidays->firstItem() }}", "{{ $ptms->firstItem() }}"),
    ("{{ $holidays->lastItem() }}", "{{ $ptms->lastItem() }}"),
    ("{{ $holidays->total() }}", "{{ $ptms->total() }}"),
    ("@if($holidays->hasPages())", "@if($ptms->hasPages())"),
    ("$currentPage = $holidays->currentPage();", "$currentPage = $ptms->currentPage();"),
    ("$lastPage = $holidays->lastPage();", "$lastPage = $ptms->lastPage();"),
    ("@if($holidays->onFirstPage())", "@if($ptms->onFirstPage())"),
    ("$holidays->previousPageUrl()", "$ptms->previousPageUrl()"),
    ("$holidays->url($page)", "$ptms->url($page)"),
    ("@if($holidays->hasMorePages())", "@if($ptms->hasMorePages())"),
    ("$holidays->nextPageUrl()", "$ptms->nextPageUrl()"),
]
for a,b in repls:
    if a not in c:
        print("INDEX MISS:", repr(a[:80]))
    else:
        c = c.replace(a,b)
p.write_text(c, encoding="utf-8")
print("index leftover holiday:", c.lower().count("holiday"))

# ---- CREATE ----
p = root / "create.blade.php"
c = p.read_text(encoding="utf-8")
repls = [
    ("@section('title', 'Add Holiday')", "@section('title', 'Add PTM')"),
    ("Academic Calendar <span>/</span> Holiday <span>/</span> Add", "Academic Calendar <span>/</span> PTM <span>/</span> Add"),
    ("Add Holiday", "Add PTM"),
    ("route('holiday.index')", "route('ptm.index')"),
    ("route('holiday.store')", "route('ptm.store')"),
    ("Holiday details", "PTM details"),
    ("Name the holiday and set when it starts and ends.", "Name the meeting and set when it starts and ends."),
    ("Holiday Name", "PTM Name"),
    ("Enter holiday name", "Enter PTM name"),
    ("Leave empty for a single day holiday.", "Leave empty for a single day PTM."),
    ("Choose who this holiday applies to.", "Choose who this PTM applies to."),
    ("Holiday For", "PTM For"),
    ('name="holiday_for_type"', 'name="ptm_for_type"'),
    ('id="holidayFor"', 'id="ptmFor"'),
    ("Select Holiday For", "Select PTM For"),
    ('data-options=\'["Teaching","Non Teaching","Administration"]\'', 'data-options=\'["Teaching","Non Teaching","Administration","Account","Transport"]\''),
    ('data-options=\'["Pre Primary","Primary","Secondary","Higher Secondary"]\'', 'data-options=\'["Science","Commerce","Arts","General"]\''),
    ('data-options=\'["Standard 1","Standard 2","Standard 3","Standard 4","Standard 5","Standard 6","Standard 7","Standard 8","Standard 9","Standard 10","Standard 11","Standard 12"]\'', 'data-options=\'["1","2","3","4","5","6","7","8","9","10","11","12"]\''),
    ("Control how people are told about this holiday.", "Control how people are told about this PTM."),
    ("Save Holiday", "Save PTM"),
    ("HOLIDAY FOR", "PTM FOR"),
    ("const holidayFor = document.getElementById('holidayFor');", "const ptmFor = document.getElementById('ptmFor');"),
    ("function updateHolidaySections()", "function updatePtmSections()"),
    ("if (holidayFor.value ===", "if (ptmFor.value ==="),
    ("holidayFor.addEventListener", "ptmFor.addEventListener"),
    ("updateHolidaySections", "updatePtmSections"),
]
for a,b in repls:
    if a not in c:
        print("CREATE MISS:", repr(a[:90]))
    else:
        c = c.replace(a,b)
p.write_text(c, encoding="utf-8")

# ---- EDIT ----
p = root / "edit.blade.php"
c = p.read_text(encoding="utf-8")
repls = [
    ("@section('title', 'Edit Holiday')", "@section('title', 'Edit PTM')"),
    ("Academic Calendar <span>/</span> Holiday <span>/</span> Edit", "Academic Calendar <span>/</span> PTM <span>/</span> Edit"),
    ("Edit Holiday", "Edit PTM"),
    ("route('holiday.index')", "route('ptm.index')"),
    ("route('holiday.update', $holiday)", "route('ptm.update', $ptm)"),
    ("Holiday details", "PTM details"),
    ("Name the holiday and set when it starts and ends.", "Name the meeting and set when it starts and ends."),
    ("Holiday Name", "PTM Name"),
    ("old('title', $holiday->title)", "old('title', $ptm->title)"),
    ("Enter holiday name", "Enter PTM name"),
    ("old('start_date', $holiday->start_date ?? $holiday->date)", "old('start_date', $ptm->start_date ?? $ptm->date)"),
    ("old('end_date', $holiday->end_date)", "old('end_date', $ptm->end_date)"),
    ("Leave empty for a single day holiday.", "Leave empty for a single day PTM."),
    ("Choose who this holiday applies to.", "Choose who this PTM applies to."),
    ("Holiday For", "PTM For"),
    ("$holidayFor = $holiday->holiday_for ?? [];", "$ptmFor = $ptm->ptm_for ?? [];"),
    ("if (is_string($holidayFor)) {\n                        $holidayFor = json_decode($holidayFor, true) ?? [];\n                    }\n\n                    $holidayForValue = $holidayFor[0] ?? '';",
     "if (is_string($ptmFor)) {\n                        $ptmFor = json_decode($ptmFor, true) ?? [];\n                    }\n\n                    $ptmForValue = $ptmFor[0] ?? '';"),
    ('name="holiday_for_type"', 'name="ptm_for_type"'),
    ('id="holidayFor"', 'id="ptmFor"'),
    ("Select Holiday For", "Select PTM For"),
    ("$holidayForValue ===", "$ptmForValue ==="),
    ('data-options=\'["Teaching","Non Teaching","Administration"]\'', 'data-options=\'["Teaching","Non Teaching","Administration","Account","Transport"]\''),
    ('data-options=\'["Pre Primary","Primary","Secondary","Higher Secondary"]\'', 'data-options=\'["Science","Commerce","Arts","General"]\''),
    ('data-options=\'["Standard 1","Standard 2","Standard 3","Standard 4","Standard 5","Standard 6","Standard 7","Standard 8","Standard 9","Standard 10","Standard 11","Standard 12"]\'', 'data-options=\'["1","2","3","4","5","6","7","8","9","10","11","12"]\''),
    ("data-selected='@json($holiday->staff_departments ?? [])'", "data-selected='@json(old('staff_departments', $ptm->staff_departments ?? []))'"),
    ("data-selected='@json($holiday->student_departments ?? [])'", "data-selected='@json(old('student_departments', $ptm->student_departments ?? []))'"),
    ("data-selected='@json($holiday->standards ?? [])'", "data-selected='@json(old('standards', $ptm->standards ?? []))'"),
    ("data-selected='@json($holiday->divisions ?? [])'", "data-selected='@json(old('divisions', $ptm->divisions ?? []))'"),
    ("Control how people are told about this holiday.", "Control how people are told about this PTM."),
    ("old('send_sms', $holiday->send_sms)", "old('send_sms', $ptm->send_sms)"),
    ("Update Holiday", "Update PTM"),
    ("HOLIDAY FOR", "PTM FOR"),
    ("const holidayFor = document.getElementById('holidayFor');", "const ptmFor = document.getElementById('ptmFor');"),
    ("function updateHolidaySections()", "function updatePtmSections()"),
    ("if (holidayFor.value ===", "if (ptmFor.value ==="),
    ("holidayFor.addEventListener", "ptmFor.addEventListener"),
    ("updateHolidaySections", "updatePtmSections"),
]
for a,b in repls:
    if a not in c:
        print("EDIT MISS:", repr(a[:100]))
    else:
        c = c.replace(a,b)
p.write_text(c, encoding="utf-8")

# ---- EXPORT ----
p = root / "export.blade.php"
c = p.read_text(encoding="utf-8")
repls = [
    ("Academic Calendar <span>/</span> Holiday <span>/</span> Export", "Academic Calendar <span>/</span> PTM <span>/</span> Export"),
    ("Export Holidays", "Export PTM"),
    ("route('holiday.index')", "route('ptm.index')"),
    ("<span>Holiday Name</span>", "<span>PTM Name</span>"),
    ("route('holiday.export.excel')", "route('ptm.export.excel')"),
    ("route('holiday.export.pdf')", "route('ptm.export.pdf')"),
]
for a,b in repls:
    if a not in c:
        print("EXPORT MISS:", repr(a[:90]))
    else:
        c = c.replace(a,b)
p.write_text(c, encoding="utf-8")
print("done")
