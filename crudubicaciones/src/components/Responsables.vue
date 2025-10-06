<template>
  <div class="p-6">
    <!-- Titulo -->
    <h2 class="text-2xl font-bold mb-4">Gestión de Responsables</h2>

    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-5xl mx-auto">

      <!-- BUSCADOR -->
      <div class="search-wrapper">
        <input
          type="search"
          v-model="query"
          placeholder="Buscar por código, documento, nombre, cargo o teléfono..."
          aria-label="Buscar responsables"
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
          Mostrando <strong>{{ filteredResponsables.length }}</strong> de {{responsables.length }}
        </div>
      </div>
      
      <!-- Tarjeta Principal -->
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Código</th>
            <th>Documento</th>
            <th>Nombres y Apellidos</th>
            <th>Cargo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="r in filteredResponsables" 
            :key="r.id"
          >
            <td>{{ r.id }}</td>
            <td>{{ r.Documento }}</td>
            <td>{{ r.Nombre_y_Apellidos }}</td>
            <td>{{ r.Cargo }}</td>
            <td>{{ r.Telefono }}</td>
            <td class="p-3 border-b border-gray-200 flex gap-2">

              <!-- Botón Editar -->
              <button
                @click="editar(r)"
                class="btn-accion btn-editar"
              >
                ✏️ Editar
              </button>

              <!-- Botón Eliminar -->
              <button
                @click="eliminar(r.id)"
                class="btn-accion btn-eliminar"
              >
                🗑️ Eliminar
              </button>
            </td>

            <!-- MODAL -->
            <div v-if="showModal" class="modal-overlay">
              <div class="modal">
                <h3>Añadir nuevo responsable</h3>

                <form @submit.prevent="guardarNuevoResponsable">
                  <label>Documento</label>
                  <input v-model="newResponsable.documento" required />

                  <label>Nombres y apellidos</label>
                  <input v-model="newResponsable.nombreape" required />
                  
                  <label>Cargo</label>
                  <input v-model="newResponsable.cargo" required />

                  <label>Teléfono</label>
                  <input v-model="newResponsable.telefono" required />

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
  name: "ResponsablesView",

  data() {
    return {
      query: "",
      responsables: [],
      showModal: false,  
      newResponsable: {    
        documento: "",
        nombreape: "",
        cargo: "",
        telefono: ""
      }
    }
  },

  mounted() {
    fetch("http://localhost/responsables/")
      .then(res => res.json())
      .then(data => {
        console.log("Datos recibidos:", data)
        this.responsables = data
      })
      .catch(err => console.error("Error al cargar responsables:", err))
  },

  computed: {
    filteredResponsables() {
      const q = this.query ? this.query.trim().toLowerCase() : ""
      if (!q) return this.responsables

      return this.responsables.filter(r => {
        const fields = [
          r.id,
          r.Documento,
          r.Nombre_y_Apellidos,
          r.Cargo,
          r.Telefono
        ]

        return fields.some(f => {
          return f && f.toString().toLowerCase().includes(q)
        })
      })
    }
  },

  methods: {
    editar(r) {
      this.newResponsable = {
        id: r.id,
        documento: r.Documento,
        nombreape: r.Nombre_y_Apellidos,
        cargo: r.Cargo,
        telefono: r.Telefono
      }
      this.showModal = true
    },

    cargarDispositivos() {
      fetch("http://localhost/dispositivos/")
        .then(res => res.json())
        .then(data => { this.dispositivos = data })
        .catch(err => console.error("Error al cargar dispositivos:", err))
    },

    cargarResponsables() {
      fetch("http://localhost/responsables/")
        .then(res => res.json())
        .then(data => { this.responsables = data })
        .catch(err => {
          console.error("Error al cargar responsables:", err)
          this.responsables = []
        })
    },
    
    eliminar(id) {
      // Paso 1: Verificar dispositivos antes de eliminar
      fetch("http://localhost/dispositivos/")
        .then(res => res.json())
        .then(data => {
          const dispositivos = Array.isArray(data) ? data : data.data || []

          // Buscar los dispositivos relacionados con este responsable
          const relacionados = dispositivos.filter(d =>
            d.Codigo_Responsable == id 
          )

          // Si existen relaciones, mostrar mensaje y abortar eliminación
          if (relacionados.length > 0) {
            const lista = relacionados
              .map(d => `• ${d.Numero_Activo}: ${d.Equipo} ${d.Modelo}`)
              .join("\n")

            alert(
              "⚠️ No se puede eliminar este responsable porque está asociado a los siguientes dispositivos:\n\n" +
              lista
            )
            return
          }

          // Paso 2: Confirmar eliminación si no hay vínculos
          if (confirm("¿Estás seguro de eliminar este responsable?")) {
            fetch(`http://localhost/responsables/?borrar=${id}`)
              .then(res => res.json())
              .then(data => {
                if (data.success === 1) {
                  this.cargarResponsables()
                  alert("Responsable eliminado correctamente")
                } else {
                  alert("Error al eliminar el responsable")
                }
              })
              .catch(err => {
                console.error("Error:", err)
                alert("Error al eliminar el responsable")
              })
          }
        })
        .catch(err => {
          console.error("Error al verificar dispositivos:", err)
          alert("Error al verificar relaciones antes de eliminar.")
        })
    },



    guardarNuevoResponsable() {
      const url = this.newResponsable.id ? 
        `http://localhost/responsables/?actualizar=${this.newResponsable.id}` : 
        "http://localhost/responsables/?insertar"
      
      const method = "POST"
      const headers = {
        "Content-Type": "application/json"
      }
      
      const body = JSON.stringify({
        documento: this.newResponsable.documento,
        nombreape: this.newResponsable.nombreape,
        cargo: this.newResponsable.cargo,
        telefono: this.newResponsable.telefono
      })

      fetch(url, { method, headers, body })
        .then(res => res.json())
        .then(data => {
          if (data.success === 1) {
            this.cargarResponsables()
            this.showModal = false
            this.newResponsable = { documento: "", nombreape: "", cargo: "", telefono: "" }
            alert("Responsable guardado correctamente")
          } else {
            alert("Error al guardar el responsable")
          }
        })
        .catch(err => {
          console.error("Error:", err)
          alert("Error al guardar el responsable")
        })
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
  padding: 0.75rem 1rem;
  border-bottom: 3px solid #444; 
}

td {
  font-family: 'Segoe UI';
  font-size: 1.1rem;
  padding: 0.75rem 1rem;
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
</style>
