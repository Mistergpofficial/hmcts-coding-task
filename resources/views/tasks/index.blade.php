<!DOCTYPE html>
<html>
<head>
    <title>HMCTS Coding Task</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { margin-bottom: 20px; }
        form, table { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        table th { background: #f5f5f5; }
        button { cursor: pointer; }
        .edit-row { background: #ffffe0; }
    </style>
</head>
<body>

<h1>HMCTS Coding Task</h1>

<h3>Create New Task</h3>
<form id="create-form">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="description" placeholder="Description">
    <select name="status">
        <option value="pending">Pending</option>
        <option value="in_progress">In progress</option>
        <option value="completed">Completed</option>
    </select>
    <input type="datetime-local" name="due_at" required>
    <button type="submit">Add Task</button>
</form>

<h3>All Tasks</h3>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Due</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="task-table-body"></tbody>
</table>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

async function loadTasks() {
    const res = await fetch('/api/tasks');
    const tasks = await res.json();

    const tbody = document.getElementById('task-table-body');
    tbody.innerHTML = '';

    tasks.forEach(task => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${task.title}</td>
            <td>${task.status}</td>
            <td>${task.due_at}</td>
            <td>${task.description ?? ''}</td>
            <td>
                <button onclick="editTask(${task.id})">Edit</button>
                <button onclick="deleteTask(${task.id})">Delete</button>
            </td>
        `;

        tbody.appendChild(row);
    });
}

document.getElementById('create-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;

    const data = {
        title: form.title.value,
        description: form.description.value,
        status: form.status.value,
        due_at: form.due_at.value,
    };

    await fetch('/api/tasks', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify(data)
    });

    form.reset();
    loadTasks();
});

async function deleteTask(id) {
    const res = await fetch(`/api/tasks/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json'
        }
    });

    if (res.ok) {
        loadTasks();
    } else {
        alert('Failed to delete task');
    }
}


async function editTask(id) {
    const res = await fetch(`/api/tasks/${id}`);
    const task = await res.json();

    const newTitle = prompt('Edit title', task.title);
    if (newTitle === null) return;

    const newStatus = prompt('Edit status (pending, in_progress, completed)', task.status);
    if (newStatus === null) return;

    const newDue = prompt('Edit due date (YYYY-MM-DD HH:MM:SS)', task.due_at);
    if (newDue === null) return;

    const newDesc = prompt('Edit description', task.description ?? '');
    if (newDesc === null) return;

    const updateRes = await fetch(`/api/tasks/${id}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            title: newTitle,
            status: newStatus,
            due_at: newDue,
            description: newDesc
        })
    });

    if (updateRes.ok) {
        loadTasks();
    } else {
        alert('Failed to update task');
    }
}


loadTasks();
</script>

</body>
</html>
