<template>
  <div class="p-6">
    <!-- Título -->
    <h2 class="text-2xl font-bold mb-4">Gestión de Ubicaciones</h2>

    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-5xl mx-auto">
      <!-- BUSCADOR -->
      <div class="search-wrapper">
        <input
          type="search"
          v-model="query"
          placeholder="Buscar por código, servicio, ubicación o teléfono..."
          aria-label="Buscar ubicaciones"
          class="search-input"
        />

        <!-- Botón Añadir -->
        <button
          @click="showModal = true"
          class="btn-accion btn-anadir"
        >
          ➕ Añadir
        </button>

        <div class="search-meta">
          Mostrando <strong>{{ filteredUbicaciones.length }}</strong> de {{ ubicaciones.length }}
        </div>
      </div>

    <!-- Tarjeta principal -->
      <table class="w-full border border-gray-300 rounded-lg text-sm">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="p-3 border-b border-gray-300 font-semibold">Código</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Servicio</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Ubicación</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Teléfono</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody>

          <tr
            v-for="u in filteredUbicaciones"
            :key="u.Codigo"
            class="hover:bg-gray-50"
          >
            <td class="p-3 border-b border-gray-200">{{ u.Codigo }}</td>
            <td class="p-3 border-b border-gray-200">{{ u.Servicio }}</td>
            <td class="p-3 border-b border-gray-200">{{ u.Ubicacion }}</td>
            <td class="p-3 border-b border-gray-200">{{ u.Telefono }}</td>
            <td class="p-3 border-b border-gray-200 flex gap-2">
              <!-- Botón Editar -->
              <button
                @click="editar(u)"
                class="btn-accion btn-editar"
              >
                ✏️ Editar
              </button>

              <!-- Botón Eliminar -->
              <button
                @click="eliminar(u.Codigo)"
                class="btn-accion btn-eliminar"
              >
                🗑️ Eliminar
              </button>

            </td>

            <!-- MODAL -->
            <div v-if="showModal" class="modal-overlay">
              <div class="modal">
                <h3>Añadir nueva ubicación</h3>

                <form @submit.prevent="guardarNuevaUbicacion">
                  <label>Código</label>
                  <input v-model="newUbicacion.Codigo" required />

                  <label>Servicio</label>
                  <input v-model="newUbicacion.Servicio" required />

                  <label>Ubicación</label>
                  <input v-model="newUbicacion.Ubicacion" required />

                  <label>Teléfono</label>
                  <input v-model="newUbicacion.Telefono" required />

                  <div class="modal-actions">
                    <button type="submit" class="btn-accion btn-anadir">Guardar</button>
                    <button type="button" class="btn-accion btn-eliminar" @click="showModal = false">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>

          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script>
export default {
  name: "UbicacionesView",

  data() {
    return {
      query: "",
      ubicaciones: [
        { Codigo: "U001", Servicio: "Cardiología", Ubicacion: "Piso 1", Telefono: "123456" },
        { Codigo: "U002", Servicio: "Neurología", Ubicacion: "Piso 2", Telefono: "654321" }
      ],
      showModal: false,  
      newUbicacion: {    
        Codigo: "",
        Servicio: "",
        Ubicacion: "",
        Telefono: ""
      }
    }
  },

  mounted() {
    fetch("http://localhost/ubicaciones/")
      .then(res => res.json())
      .then(data => {
        console.log("Datos recibidos:", data)
        this.ubicaciones = data
      })
      .catch(err => console.error("Error al cargar ubicaciones:", err))
  },

  computed: {
    filteredUbicaciones() {
      const q = this.query ? this.query.trim().toLowerCase() : ""
      if (!q) return this.ubicaciones

      return this.ubicaciones.filter(u => {
        const fields = [
          u.Codigo,
          u.Servicio,
          u.Ubicacion,
          u.Telefono
        ]

        return fields.some(f => {
          return f && f.toString().toLowerCase().includes(q)
        })
      })
    }
  },

  methods: {
    editar(u) {
      alert("Editar " + u.Servicio)
    },
    
    eliminar(id) {
      alert("Eliminar registro con código " + id)
    },

    guardarNuevaUbicacion() {
      this.ubicaciones.push({ ...this.newUbicacion })

      this.newUbicacion = { Codigo: "", Servicio: "", Ubicacion: "", Telefono: "" }

      this.showModal = false
    }
  }
}
</script>

<style scoped>

h2 {
  font-family: 'Segoe UI';
  font-size: 2rem;
  text-align: center;
}

table {
  margin-left: auto;
  margin-right: auto;
  border-collapse: collapse;
}

th {
  font-family: 'Segoe UI';
  font-size: 1.7rem;
  padding: 0.75rem 1.3rem;
  border-bottom: 3px solid #444; 
}

td {
  font-family: 'Segoe UI';
  font-size: 1.1rem;
  padding: 0.75rem 1.5rem;
}

.search-wrapper {
  display: flex;
  gap: 0.75rem;
  justify-content: center;
  align-items: center;
  margin-bottom: 1rem;
}

.search-input {
  width: 60%; 
  padding: 0.6rem 0.9rem;
  border-radius: 0.6rem;
  border: 1px solid #e0e0e0;
  font-size: 1rem;
  outline: none;
  box-shadow: none;
}

.search-input:focus {
  border-color: #9ca3af;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.search-meta {
  font-size: 0.95rem;
  color: #374151;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal {
  background: #fff;
  padding: 2rem;
  border-radius: 1rem;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.modal h3 {
  margin-bottom: 1rem;
  font-size: 1.25rem;
}

.modal label {
  display: block;
  margin: 0.5rem 0 0.25rem;
  font-weight: bold;
}

.modal input {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 0.5rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.btn-accion {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 0.9rem;
  font-size: 0.95rem;
  font-weight: 500;
  border-radius: 0.6rem;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
  cursor: pointer;
  border: none;
}

.btn-editar {
  background-color: #facc15;
  color: #1f2937;
}
.btn-editar:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.08);
}


.btn-eliminar {
  background-color: #ef4444;
  color: #fff;
}
.btn-eliminar:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.08);
}

.btn-anadir {
  background-color: #3b82f6; /* azul principal */
  color: #fff;
}
.btn-anadir:hover {
  background-color: #2563eb; /* azul más oscuro */
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.08);
}
</style>
