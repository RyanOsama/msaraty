<!DOCTYPE html>
<html lang="ar">
<head>
    
    <meta charset="UTF-8">
    <title>Admin Test Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-4">

    <h1 class="mb-4 text-center">لوحة تجريبية للإدارة</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- ================= USERS ================= -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">👤 المستخدمون</div>
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\User::all() as $user)
                    <tr>
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <td>{{ $user->id }}</td>
                            <td><input class="form-control" name="username" value="{{ $user->username }}"></td>

                            <td>
                                <select class="form-select" name="role_id">
                                    @foreach(\App\Models\Role::whereIn('name',['student','driver','admin'])->get() as $role)
                                        <option value="{{ $role->id }}" @selected($user->role_id==$role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select class="form-select" name="status">
                                    <option value="pending" @selected($user->status=='pending')>pending</option>
                                    <option value="approved" @selected($user->status=='approved')>approved</option>
                                    <option value="rejected" @selected($user->status=='rejected')>rejected</option>
                                </select>
                            </td>

                            <td><button class="btn btn-sm btn-warning">تعديل</button></td>
                        </form>

                        <td>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <hr>

            <h5>➕ إضافة مستخدم</h5>
            <form class="row g-2" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="col-md-3">
                    <input class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="role_id">
                        @foreach(\App\Models\Role::whereIn('name',['student','driver','admin'])->get() as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="approved">approved</option>
                        <option value="pending">pending</option>
                        <option value="rejected">rejected</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success w-100">إضافة</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= ROUTES ================= -->
   <!-- ================= ROUTES ================= -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">🛣️ الخطوط</div>
    <div class="card-body">

        <!-- إضافة خط -->
        <form class="d-flex gap-2 mb-3" method="POST" action="{{ route('admin.routes.store') }}">
            @csrf
            <input class="form-control" name="route_name" placeholder="اسم الخط" required>
            <button class="btn btn-success">إضافة</button>
        </form>

        <!-- جدول الخطوط -->
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>اسم الخط</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Route::all() as $route)
                <tr>
                    <!-- فورم التعديل -->
                    <form method="POST" action="{{ route('admin.routes.update', $route->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $route->id }}</td>
                        <td>
                            <input
                                class="form-control"
                                name="route_name"
                                value="{{ $route->route_name }}"
                            >
                        </td>

                        <!-- زر التعديل -->
                        <td>
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                تعديل
                            </button>
                        </td>
                    </form>

                    <!-- زر الحذف -->
                    <td>
                        <form method="POST" action="{{ route('admin.routes.destroy', $route->id) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('متأكد من الحذف؟')"
                            >
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

    <!-- ================= STATIONS ================= -->
    <div class="card mb-4">
    <div class="card-header bg-info text-white">📍 المحطات</div>
    <div class="card-body">

        <!-- إضافة محطة -->
        <form class="row g-2 mb-3" method="POST" action="{{ route('admin.stations.store') }}">
            @csrf
            <div class="col">
                <input class="form-control" name="station_name" placeholder="اسم المحطة" required>
            </div>
            <div class="col">
                <input class="form-control" name="location_x" placeholder="X" required>
            </div>
            <div class="col">
                <input class="form-control" name="location_y" placeholder="Y" required>
            </div>
            <div class="col">
                <input class="form-control" name="description" placeholder="الوصف">
            </div>
            <div class="col">
                <button class="btn btn-success w-100">إضافة</button>
            </div>
        </form>

        <!-- جدول المحطات -->
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>اسم المحطة</th>
                    <th>X</th>
                    <th>Y</th>
                    <th>الوصف</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Station::all() as $station)
                <tr>
                    <!-- فورم التعديل -->
                    <form method="POST" action="{{ route('admin.stations.update', $station->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $station->id }}</td>
                        <td>
                            <input class="form-control" name="station_name" value="{{ $station->station_name }}">
                        </td>
                        <td>
                            <input class="form-control" name="location_x" value="{{ $station->location_x }}">
                        </td>
                        <td>
                            <input class="form-control" name="location_y" value="{{ $station->location_y }}">
                        </td>
                        <td>
                            <input class="form-control" name="description" value="{{ $station->description }}">
                        </td>

                        <!-- زر التعديل -->
                        <td>
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                تعديل
                            </button>
                        </td>
                    </form>

                    <!-- زر الحذف -->
                    <td>
                        <form method="POST" action="{{ route('admin.stations.destroy', $station->id) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('متأكد من الحذف؟')"
                            >
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>


    <!-- ================= ROUTE STATIONS ================= -->
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        🔗 ربط خط بمحطات (بالترتيب)
    </div>

    <div class="card-body">
        <form class="row g-3" method="POST" action="{{ route('admin.route-stations.store') }}">
            @csrf

            <!-- الخط -->
            <div class="col-md-4">
                <label class="form-label fw-bold">🛣️ الخط</label>
                <select class="form-select" name="route_id" required>
                    <option value="">-- اختر خط --</option>
                    @foreach(\App\Models\Route::all() as $route)
                        <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- المحطات -->
            <div class="col-md-6">
                <label class="form-label fw-bold">📍 المحطات + الترتيب</label>

                <div class="border rounded p-3" style="max-height: 260px; overflow-y: auto;">
                    @foreach(\App\Models\Station::all() as $index => $station)
                        <div class="d-flex align-items-center mb-2 gap-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="stations[{{ $index }}][station_id]"
                                value="{{ $station->id }}"
                                id="station_{{ $station->id }}"
                            >

                            <label class="form-check-label flex-grow-1" for="station_{{ $station->id }}">
                                {{ $station->station_name }}
                            </label>

                            <input
                                type="number"
                                class="form-control form-control-sm"
                                name="stations[{{ $index }}][order]"
                                placeholder="الترتيب"
                                style="width: 90px;"
                                min="1"
                            >
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- زر -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    ربط
                </button>
            </div>
        </form>
    </div>
</div>

</div>

</div>

<!-- ================= ROUTE ↔ STATIONS MANAGE ================= -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        📋 إدارة محطات الخطوط
    </div>

    <div class="card-body">

        @foreach(\App\Models\Route::with('stations')->get() as $route)
            <div class="border rounded mb-4">

                <div class="bg-light px-3 py-2 fw-bold">
                    🛣️ {{ $route->route_name }}
                </div>

                <!-- إضافة محطة جديدة -->
                <div class="p-3 border-bottom">
                 <!-- إضافة أكثر من محطة لنفس الخط -->
<!-- إضافة محطات غير مرتبطة فقط -->
<form method="POST" action="{{ route('admin.route-stations.store') }}">
    @csrf

    <input type="hidden" name="route_id" value="{{ $route->id }}">

    @php
        $linkedStationIds = $route->stations->pluck('id')->toArray();
        $availableStations = \App\Models\Station::whereNotIn('id', $linkedStationIds)->get();
    @endphp

    @if($availableStations->count())

        <div class="border rounded p-3 mb-3" style="max-height:260px; overflow-y:auto;">

            @foreach($availableStations as $index => $station)
                <div class="d-flex align-items-center gap-2 mb-2">

                    <!-- اختيار المحطة -->
                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="stations[{{ $index }}][station_id]"
                        value="{{ $station->id }}"
                    >

                    <span class="flex-grow-1">
                        {{ $station->station_name }}
                    </span>

                    <!-- الترتيب -->
                    <input
                        type="number"
                        name="stations[{{ $index }}][order]"
                        class="form-control form-control-sm"
                        placeholder="الترتيب"
                        style="width:90px"
                        min="1"
                    >
                </div>
            @endforeach

        </div>

        <button class="btn btn-success w-100">
            إضافة المحطات المختارة
        </button>

    @else
        <div class="alert alert-info text-center">
            ✅ جميع المحطات مرتبطة بهذا الخط
        </div>
    @endif
</form>

                </div>

                <!-- جدول المحطات -->
                @if($route->stations->count())
                    <table class="table table-bordered text-center align-middle mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>اسم المحطة</th>
                                <th>الترتيب</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($route->stations as $station)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $station->station_name }}</td>

                                <!-- تعديل الترتيب -->
                                <td>
                                    <form method="POST" action="{{ route('admin.route-stations.order') }}"
                                          class="d-flex gap-2 justify-content-center">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="route_id" value="{{ $route->id }}">
                                        <input type="hidden" name="station_id" value="{{ $station->id }}">

                                        <input
                                            type="number"
                                            name="order"
                                            value="{{ $station->pivot->order }}"
                                            class="form-control form-control-sm text-center"
                                            style="width:80px"
                                            min="1"
                                        >

                                        <button class="btn btn-sm btn-primary">
                                            حفظ
                                        </button>
                                    </form>
                                </td>

                                <!-- حذف -->
                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.route-stations.destroy') }}"
                                          onsubmit="return confirm('متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" name="route_id" value="{{ $route->id }}">
                                        <input type="hidden" name="station_id" value="{{ $station->id }}">

                                        <button class="btn btn-sm btn-danger">
                                            حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3 text-center text-muted">
                        لا توجد محطات مرتبطة بهذا الخط
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</div>

<div class="container my-4">

    <h4 class="mb-4 fw-bold text-center">🎓 إدارة الطلاب</h4>

    {{-- عرض الطلاب --}}
    @foreach(\App\Models\Student::with(['days'])->get() as $student)

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">
                طالب رقم: {{ $student->id }}
            </div>

            <div class="card-body">

                <form class="row g-3"
                      method="POST"
                      action="{{ route('students.update', $student->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="col-md-2">
                        <label class="form-label small">الاسم</label>
                        <input class="form-control form-control-sm"
                               name="name"
                               value="{{ $student->name }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">الرقم الجامعي</label>
                        <input class="form-control form-control-sm"
                               name="university_number"
                               value="{{ $student->university_number }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">الجوال</label>
                        <input class="form-control form-control-sm"
                               name="phone"
                               value="{{ $student->phone }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">المدينة</label>
                        <input class="form-control form-control-sm"
                               name="city"
                               value="{{ $student->city }}">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small">الجنس</label>
                        <select class="form-select form-select-sm" name="gender">
                            <option value="Male" @selected($student->gender=='رجل')>رجل</option>
                            <option value="Female" @selected($student->gender=='امرأة')>امرأة</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small">الحالة</label>
                        <select class="form-select form-select-sm" name="state">
                            <option value="Active" @selected($student->state=='Active')>Active</option>
                            <option value="Inactive" @selected($student->state=='Inactive')>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">الجامعة</label>
                        <select class="form-select form-select-sm" name="university_id">
                            @foreach(\App\Models\University::all() as $uni)
                                <option value="{{ $uni->id }}"
                                    @selected($student->university_id==$uni->id)>
                                    {{ $uni->university_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">الكلية</label>
                        <select class="form-select form-select-sm" name="college_id">
                            @foreach(\App\Models\College::all() as $college)
                                <option value="{{ $college->id }}"
                                    @selected($student->college_id==$college->id)>
                                    {{ $college->college_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">القسم</label>
                        <select class="form-select form-select-sm" name="department_id">
                            @foreach(\App\Models\Department::all() as $department)
                                <option value="{{ $department->id }}"
                                    @selected($student->department_id==$department->id)>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">المستوى</label>
                        <select class="form-select form-select-sm" name="level_id">
                            @foreach(\App\Models\Level::all() as $level)
                                <option value="{{ $level->id }}"
                                    @selected($student->level_id==$level->id)>
                                    {{ $level->level_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">الأيام</label>
                        <div class="border rounded p-2 bg-light"
                             style="max-height:120px; overflow-y:auto;">
                            @foreach(\App\Models\Day::all() as $day)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="days[]"
                                           value="{{ $day->id }}"
                                           @checked($student->days->contains($day->id))>
                                    <label class="form-check-label small">
                                        {{ $day->day_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button class="btn btn-warning btn-sm w-100">
                            حفظ التعديل
                        </button>
                    </div>

                </form>

                <form method="POST"
                      action="{{ route('students.destroy', $student->id) }}"
                      class="mt-2 text-end">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        حذف الطالب
                    </button>
                </form>

            </div>
        </div>

    @endforeach

{{-- إضافة طالب --}}
<div class="card shadow mt-5">
    <div class="card-header bg-success text-white fw-bold">
        ➕ إضافة طالب جديد
    </div>

    <div class="card-body">

        <form class="row g-3" method="POST" action="{{ route('students.store') }}">
            @csrf

            <div class="col-md-2">
                <input class="form-control form-control-sm" name="name" placeholder="الاسم" required>
            </div>

            <div class="col-md-2">
                <input class="form-control form-control-sm" name="university_number" placeholder="الرقم الجامعي" required>
            </div>

            <div class="col-md-2">
                <input class="form-control form-control-sm" name="phone" placeholder="الجوال" required>
            </div>

            <div class="col-md-2">
                <input class="form-control form-control-sm" name="city" placeholder="المدينة" required>
            </div>

            <div class="col-md-1">
                <select class="form-select form-select-sm" name="gender">
                     <option value="" selected disabled>اختر النوع</option>
                    <option value="رجل">رجل</option>
                    <option value="امرأة">امرأة</option>
                </select>
            </div>

            <div class="col-md-1">
                <select class="form-select form-select-sm"  name="state">
                    <option value="" selected disabled>اختر الحاله</option>
                    <option value="Active">نشط</option>
                    <option value="Inactive">غير نشط</option>
                </select>
            </div>

            {{-- الجامعة --}}
            <div class="col-md-2">
                <select class="form-select form-select-sm"  name="university_id">
                     <option value="" selected disabled>اختر الجامعة</option>
                    @foreach(\App\Models\University::all() as $uni)
                        <option value="{{ $uni->id }}">{{ $uni->university_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- الكلية --}}
            <div class="col-md-2">
                <select class="form-select form-select-sm" name="college_id">
                     <option value="" selected disabled>اختر الكليه</option>
                    @foreach(\App\Models\College::all() as $college)
                        <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- القسم --}}
            <div class="col-md-2">
                <select name="department_id" id="department_id" class="form-select form-select-sm">
                   <option value="" selected disabled>اختر القسم</option>
                    @foreach(\App\Models\Department::all() as $department)
                        <option value="{{ $department->id }}">
                            {{ $department->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- المستوى --}}
            <div class="col-md-2">
                <select name="level_id" id="level_id" class="form-select form-select-sm">
 <option value="" selected disabled>اختر المستوى</option>                </select>
            </div>

            {{-- الأيام --}}
            <div class="col-md-3">
                <div class="border rounded p-2 bg-light" style="max-height:120px; overflow-y:auto;">
                    @foreach(\App\Models\Day::all() as $day)
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="days[]"
                                   value="{{ $day->id }}">
                            <label class="form-check-label small">
                                {{ $day->day_name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-success w-100">إضافة الطالب</button>
            </div>

        </form>

    </div>
</div>

<script>
document.getElementById('department_id').addEventListener('change', function () {

    let departmentId = this.value;

    if(!departmentId){
        document.getElementById('level_id').innerHTML =
            '<option value="">اختر المستوى</option>';
        return;
    }

    fetch('/levels-by-department/' + departmentId)
        .then(response => response.json())
        .then(data => {

            let levelSelect = document.getElementById('level_id');
            levelSelect.innerHTML = '<option value="">اختر المستوى</option>';

            data.forEach(level => {
                levelSelect.innerHTML +=
                    `<option value="${level.id}">${level.level_name}</option>`;
            });

        });

});
</script>


<!-- ================= UNIVERSITIES ================= -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white fw-bold">
        🏫 الجامعات
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>اسم الجامعة</th>
                <th>تعديل</th>
                <th>حذف</th>
            </tr>
            </thead>

            <tbody>
            @foreach(\App\Models\University::all() as $university)
                <tr>

                    <form method="POST"
                          action="{{ route('universities.update', $university->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $university->id }}</td>

                        <td>
                            <input class="form-control"
                                   name="university_name"
                                   value="{{ $university->university_name }}">
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning w-100">
                                تعديل
                            </button>
                        </td>

                    </form>

                    <td>
                        <form method="POST"
                              action="{{ route('universities.destroy', $university->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger w-100"
                                    onclick="return confirm('متأكد من الحذف؟')">
                                حذف
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <hr>

        <h5>➕ إضافة جامعة</h5>

        <form class="row g-2"
              method="POST"
              action="{{ route('universities.store') }}">
            @csrf

            <div class="col-md-4">
                <input class="form-control"
                       name="university_name"
                       placeholder="اسم الجامعة"
                       required>
            </div>

            <div class="col-md-2">
                <button class="btn btn-success w-100">
                    إضافة
                </button>
            </div>

        </form>

    </div>
</div>
<!-- ================= COLLEGES ================= -->
<div class="card mb-4">
    <div class="card-header bg-info text-white fw-bold">
        🏫 الكليات
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>اسم الكلية</th>
                <th>الجامعة</th>
                <th>تعديل</th>
                <th>حذف</th>
            </tr>
            </thead>

            <tbody>
            @foreach(\App\Models\College::with('university')->get() as $college)
                <tr>

                    <form method="POST"
                          action="{{ route('colleges.update', $college->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $college->id }}</td>

                        <td>
                            <input class="form-control"
                                   name="college_name"
                                   value="{{ $college->college_name }}">
                        </td>

                        <td>
                            <select class="form-select" name="university_id">
                                @foreach(\App\Models\University::all() as $uni)
                                    <option value="{{ $uni->id }}"
                                        @selected($college->university_id==$uni->id)>
                                        {{ $uni->university_name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning w-100">
                                تعديل
                            </button>
                        </td>

                    </form>

                    <td>
                        <form method="POST"
                              action="{{ route('colleges.destroy', $college->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger w-100"
                                    onclick="return confirm('متأكد من الحذف؟')">
                                حذف
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <hr>

        <h5>➕ إضافة كلية</h5>

        <form class="row g-2"
              method="POST"
              action="{{ route('colleges.store') }}">
            @csrf

            <div class="col-md-4">
                <input class="form-control"
                       name="college_name"
                       placeholder="اسم الكلية"
                       required>
            </div>

            <div class="col-md-4">
                <select class="form-select" name="university_id" required>
                    <option value="">اختر الجامعة</option>
                    @foreach(\App\Models\University::all() as $uni)
                        <option value="{{ $uni->id }}">
                            {{ $uni->university_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-success w-100">
                    إضافة
                </button>
            </div>

        </form>

    </div>
</div>
<hr>

<!-- ================= DEPARTMENTS ================= -->
<div class="card mb-4">
    <div class="card-header bg-secondary text-white fw-bold">
        🏢 الأقسام
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>اسم القسم</th>
                <th>الكلية</th>
                <th>الجامعة</th>
                <th>تعديل</th>
                <th>حذف</th>
            </tr>
            </thead>

            <tbody>
            @foreach(\App\Models\Department::with('college.university')->get() as $department)
                <tr>

                    <form method="POST"
                          action="{{ route('departments.update', $department->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $department->id }}</td>

                        <td>
                            <input class="form-control"
                                   name="department_name"
                                   value="{{ $department->department_name }}">
                        </td>

                        <td>
                            <select class="form-select" name="college_id">
                                @foreach(\App\Models\College::with('university')->get() as $college)
                                    <option value="{{ $college->id }}"
                                        @selected($department->college_id==$college->id)>
                                        {{ $college->college_name }}
                                        ({{ $college->university->university_name }})
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            {{ $department->college->university->university_name ?? '-' }}
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning w-100">
                                تعديل
                            </button>
                        </td>

                    </form>

                    <td>
                        <form method="POST"
                              action="{{ route('departments.destroy', $department->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger w-100"
                                    onclick="return confirm('متأكد من الحذف؟')">
                                حذف
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <hr>

        <h5>➕ إضافة قسم</h5>

        <form class="row g-2"
              method="POST"
              action="{{ route('departments.store') }}">
            @csrf

            <div class="col-md-4">
                <input class="form-control"
                       name="department_name"
                       placeholder="اسم القسم"
                       required>
            </div>

            <div class="col-md-4">
                <select class="form-select" name="college_id" required>
                    <option value="">اختر الكلية</option>
                    @foreach(\App\Models\College::with('university')->get() as $college)
                        <option value="{{ $college->id }}">
                            {{ $college->college_name }}
                            ({{ $college->university->university_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-success w-100">
                    إضافة
                </button>
            </div>

        </form>

    </div>
</div>



<hr>


<!-- ================= LEVELS ================= -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white fw-bold">
        📚 المستويات
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>اسم المستوى</th>
                <th>الأقسام المرتبطة</th>
                <th>تعديل</th>
                <th>حذف</th>
            </tr>
            </thead>

            <tbody>
            @foreach(\App\Models\Level::with('departments.college.university')->get() as $level)
                <tr>

                    <form method="POST"
                          action="{{ route('levels.update', $level->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $level->id }}</td>

                        <td>
                            <input class="form-control"
                                   name="level_name"
                                   value="{{ $level->level_name }}">
                        </td>

                        <!-- عرض الأقسام كـ Checkboxes -->
                        <td>
                            <div class="border rounded p-2 bg-light"
                                 style="max-height:160px; overflow-y:auto; min-width:300px;">

                                @foreach(\App\Models\Department::with('college.university')->get() as $department)

                                    <div class="form-check text-start mb-1">

                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="department_ids[]"
                                               value="{{ $department->id }}"
                                               id="edit_dep_{{ $level->id }}_{{ $department->id }}"
                                               @checked($level->departments->contains($department->id))>

                                        <label class="form-check-label small"
                                               for="edit_dep_{{ $level->id }}_{{ $department->id }}">

                                            <strong>{{ $department->department_name }}</strong>
                                            <span class="text-muted">
                                                ({{ $department->college->college_name }}
                                                - {{ $department->college->university->university_name }})
                                            </span>

                                        </label>

                                    </div>

                                @endforeach

                            </div>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning w-100">
                                تعديل
                            </button>
                        </td>

                    </form>

                    <td>
                        <form method="POST"
                              action="{{ route('levels.destroy', $level->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger w-100"
                                    onclick="return confirm('متأكد من الحذف؟')">
                                حذف
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

        <hr>

        <h5>➕ إضافة مستوى</h5>

        <form class="row g-2"
              method="POST"
              action="{{ route('levels.store') }}">
            @csrf

            <div class="col-md-4">
                <input class="form-control"
                       name="level_name"
                       placeholder="اسم المستوى"
                       required>
            </div>

          <div class="col-md-6">
    <label class="form-label fw-bold">اختر الأقسام</label>

    <div class="border rounded p-3 bg-light"
         style="max-height:220px; overflow-y:auto;">

        @foreach(\App\Models\Department::with('college.university')->get() as $department)

            <div class="form-check mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       name="department_ids[]"
                       value="{{ $department->id }}"
                       id="dep_{{ $department->id }}">

                <label class="form-check-label"
                       for="dep_{{ $department->id }}">

                    <strong>{{ $department->department_name }}</strong>
                    <small class="text-muted">
                        - {{ $department->college->college_name }}
                        ({{ $department->college->university->university_name }})
                    </small>

                </label>
            </div>

        @endforeach

    </div>
</div>

            <div class="col-md-2">
                <button class="btn btn-success w-100">
                    إضافة
                </button>
            </div>

        </form>

    </div>
</div>
<hr>



<!-- ================= DAYS ================= -->
<div class="card mb-4">
    <div class="card-header bg-info text-white fw-bold">
        📅 الأيام
    </div>

    <div class="card-body">

        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>اسم اليوم</th>
                <th>تعديل</th>
                <th>حذف</th>
            </tr>
            </thead>

            <tbody>
            @foreach(\App\Models\Day::all() as $day)
                <tr>
                    <form method="POST" action="{{ route('days.update', $day->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $day->id }}</td>

                        <td>
                            <input class="form-control"
                                   name="day_name"
                                   value="{{ $day->day_name }}">
                        </td>

                        <td>
                            <button class="btn btn-warning btn-sm">
                                تعديل
                            </button>
                        </td>
                    </form>

                    <td>
                        <form method="POST"
                              action="{{ route('days.destroy', $day->id) }}">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('متأكد من الحذف؟')">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <hr>

        <h5>➕ إضافة يوم</h5>

        <form class="d-flex gap-2"
              method="POST"
              action="{{ route('days.store') }}">
            @csrf

            <input class="form-control"
                   name="day_name"
                   placeholder="اسم اليوم"
                   required>

            <button class="btn btn-success">
                إضافة
            </button>
        </form>

    </div>
</div>



</body>
</html>
