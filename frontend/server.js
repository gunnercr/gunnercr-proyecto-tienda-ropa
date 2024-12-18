const express = require('express');
const path = require('path');
const app = express();
const port = 3000;

// Sirve el archivo HTML
app.use(express.static(path.join(__dirname, 'public')));

// Inicia el servidor
app.listen(port, () => {
  console.log(`Servidor escuchando en http://localhost:${port}`);
});
