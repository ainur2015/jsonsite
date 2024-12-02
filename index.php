<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Генератор JSON</title>
    <style>
        :root {
            --bg-color: #f4f4f9;
            --text-color: #333;
            --container-bg: #fff;
            --pre-bg: #f7f7f7;
            --button-bg: #007bff;
            --button-hover: #0056b3;
        }

        .dark-theme {
            --bg-color: #2c2c2c;
            --text-color: #f4f4f4;
            --container-bg: #3c3c3c;
            --pre-bg: #2f2f2f;
            --button-bg: #1e90ff;
            --button-hover: #104e8b;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: var(--container-bg);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        textarea, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: var(--pre-bg);
            color: var(--text-color);
        }

        button {
            padding: 10px 15px;
            background: var(--button-bg);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: var(--button-hover);
        }

        pre {
            background: var(--pre-bg);
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow-x: auto;
        }

        .theme-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .theme-switcher input[type="checkbox"] {
            width: 40px;
            height: 20px;
            position: relative;
            appearance: none;
            background: #ccc;
            outline: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .theme-switcher input[type="checkbox"]:checked {
            background: var(--button-bg);
        }

        .theme-switcher input[type="checkbox"]::before {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            top: 1px;
            left: 1px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .theme-switcher input[type="checkbox"]:checked::before {
            transform: translateX(20px);
        }
    </style>
</head>
<body>
    <div class="theme-switcher">
        <label for="theme-toggle">Тёмная тема</label>
        <input type="checkbox" id="theme-toggle" onclick="toggleTheme()">
    </div>

    <div class="container">
        <h1>Генератор JSON</h1>
        
        <label for="path">Путь: (пример: object.thread.count):</label>
        <input type="text" id="path" placeholder="Введите путь (например: object.thread.count)">
        
        <label for="value">Значение:</label>
        <input type="text" id="value" placeholder="Введите значение (например: 5)">
        
        <button onclick="addToJSON()">Добавить/Обновить JSON</button>
        <button onclick="downloadJSON()">Скачать JSON</button>

        <h2>Сгенерированный JSON:</h2>
        <pre id="output">{
}</pre>
    </div>

    <script>
        let jsonObject = {};

        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
        }

        function getCookie(name) {
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [key, value] = cookie.trim().split('=');
                if (key === name) return value;
            }
            return null;
        }

        function toggleTheme() {
            const isDark = document.body.classList.toggle('dark-theme');
            document.getElementById('theme-toggle').checked = isDark;
            setCookie('theme', isDark ? 'dark' : 'light', 30);
        }

        window.onload = function() {
            const savedTheme = getCookie('theme');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                document.getElementById('theme-toggle').checked = true;
            }
        };

        function setNestedValue(obj, path, value) {
            const keys = path.split('.');
            let current = obj;

            for (let i = 0; i < keys.length - 1; i++) {
                const key = keys[i];

                if (!(key in current)) {
                    current[key] = {};
                }

                current = current[key];
            }

            try {
                current[keys[keys.length - 1]] = JSON.parse(value);
            } catch (e) {
                current[keys[keys.length - 1]] = value;
            }
        }

        function addToJSON() {
            const path = document.getElementById('path').value.trim();
            const value = document.getElementById('value').value.trim();

            if (path === "") {
                alert("Путь не может быть пустым!");
                return;
            }

            setNestedValue(jsonObject, path, value);

            document.getElementById('output').textContent = JSON.stringify(jsonObject, null, 4);
            document.getElementById('path').value = '';
            document.getElementById('value').value = '';
        }

        function downloadJSON() {
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(jsonObject, null, 4));
            const downloadAnchor = document.createElement('a');
            downloadAnchor.setAttribute("href", dataStr);
            downloadAnchor.setAttribute("download", "generated.json");
            document.body.appendChild(downloadAnchor);
            downloadAnchor.click();
            downloadAnchor.remove();
        }
    </script>
</body>
</html>
