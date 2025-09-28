  <template>
    <div class="p-6">
      <!-- Título -->
      <h2>Gestión de Equipos Médicos</h2>

      <div class="bg-white rounded-2xl shadow-lg p-6 max-w-5xl mx-auto">
        <!-- BUSCADOR -->
        <div class="search-wrapper">
          <input
            type="search"
            v-model="query"
            placeholder="Buscar por número activo, equipo, marca, modelo, código de ubicación o responsable..."
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
            Mostrando <strong>{{ filteredDispositivos.length }}</strong> de {{ dispositivos.length }}
          </div>
        </div>
        
        
        <!-- Tarjeta principal -->
        <table class="w-full border border-gray-300 rounded-lg text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-3 border-b border-gray-300 font-semibold">Número activo</th>
              <th class="p-3 border-b border-gray-300 font-semibold">Equipo</th>
              <th class="p-3 border-b border-gray-300 font-semibold">Marca</th>
              <th class="p-3 border-b border-gray-300 font-semibold">Modelo</th>
              <th class="p-3 border-b border-gray-300 font-semibold">Código ubicación</th>
              <th class="p-3 border-b border-gray-300 font-semibold">Código responsable</th>
              <th class="p-3 border-b border-gray-300 font-semibold">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="d in filteredDispositivos"
              :key="d['Numero activo']"
              class="hover:bg-gray-50"
            >
              <td>{{ d["Numero Activo"] }}</td>
              <td>{{ d.Equipo }}</td>
              <td>{{ d.Marca }}</td>
              <td>{{ d.Modelo }}</td>
              <td>{{ d["Codigo Ubicacion"] }}</td>
              <td>{{ d["Codigo Responsable"] }}</td>
              <td class="p-3 border-b border-gray-200 flex gap-2"> 
                 <!-- Botón Editar -->
                <button
                  @click="editar(d)"
                  class="btn-accion btn-editar"
                >
                  ✏️ Editar
                </button>

                <!-- Botón Eliminar -->
                <button
                  @click="eliminar(d)"
                  class="btn-accion btn-eliminar"
                >
                  🗑️ Eliminar
                </button>

              </td>
            </tr>
          </tbody>
        </table>
        
        <!-- MODAL (fuera de la tabla para que no se repita) -->
        <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
          <div class="modal">
            <h3>Añadir nuevo dispositivo</h3>

            <form @submit.prevent="guardarNuevoDispositivo">
              <label>Número activo</label>
              <input v-model="newDispositivo.NumeroActivo" required />

              <label>Equipo</label>
              <input v-model="newDispositivo.Equipo" required />

              <label>Marca</label>
              <input v-model="newDispositivo.Marca" required />

              <label>Modelo</label>
              <input v-model="newDispositivo.Modelo" required />

              <label>Código Ubicación</label>
              <select v-model="newDispositivo.CodigoUbicacion" required>
                <option value="" disabled>-- Selecciona ubicación --</option>
                <option
                  v-for="u in ubicaciones"
                  :key="u.Codigo ?? u['Codigo'] ?? u.id"
                  :value="u.Codigo ?? u['Codigo'] ?? u.id"
                >
                  {{ (u.Servicio ?? u.Ubicacion ?? u['Ubicación'] ?? u.Codigo) + ' — ' + (u.Codigo ?? u['Codigo'] ?? '') }}
                </option>
                <option v-if="ubicaciones.length === 0" disabled>Cargando ubicaciones...</option>
              </select>

              <label>Código Responsable</label>
              <select v-model="newDispositivo.CodigoResponsable" required>
                <option value="" disabled>-- Selecciona responsable --</option>
                <option
                  v-for="r in responsables"
                  :key="r.id ?? r.Codigo ?? r.Documento"
                  :value="r.id ?? r.Codigo ?? r.Documento"
                >
                  {{ (r['Nombre y Apellidos'] ?? r.Nombre ?? r['Nombres y Apellidos'] ?? r.Documento ?? r.id) + ' — ' + (r.id ?? r.Documento ?? '') }}
                </option>
                <option v-if="responsables.length === 0" disabled>Cargando responsables...</option>
              </select>

              <div class="modal-actions">
                <button type="submit" class="btn-accion btn-anadir" :disabled="!canSave">Guardar</button>
                <button type="button" class="btn-accion btn-eliminar" @click="closeModal">Cancelar</button>
              </div>
            </form>
          </div>
        </div>

      </div>
      
    </div>  
  </template>

<script>
  export default {
    name: "EquiposMedicos",

    data() {
      return {
        dispositivos: [],
        ubicaciones: [],
        responsables: [],
        query: "",
        showModal: false,
        newDispositivo: {
          NumeroActivo: "",
          Equipo: "",
          Marca: "",
          Modelo: "",
          CodigoUbicacion: "",
          CodigoResponsable: ""
        }
      }
    },

    mounted() {
      fetch("http://localhost/dispositivos/")
        .then(res => res.json())
        .then(data => { this.dispositivos = data })
        .catch(err => console.error("Error al cargar dispositivos:", err))

      fetch("http://localhost/ubicaciones/")
        .then(res => res.json())
        .then(data => { this.ubicaciones = data })
        .catch(err => {
          console.error("Error al cargar ubicaciones:", err)
          this.ubicaciones = []
        })

      fetch("http://localhost/responsables/")
        .then(res => res.json())
        .then(data => { this.responsables = data })
        .catch(err => {
          console.error("Error al cargar responsables:", err)
          this.responsables = []
        })
    },

    computed: {
    filteredDispositivos() {
      const q = this.query ? this.query.trim().toLowerCase() : ""
      if (!q) return this.dispositivos

      return this.dispositivos.filter(d => {
        const fields = [
          d["Numero Activo"],
          d.Equipo,
          d.Marca,
          d.Modelo,
          d["Codigo Ubicacion"],
          d["Codigo Responsable"]
        ]

        return fields.some(f => {
          return f && f.toString().toLowerCase().includes(q)
        })
      })
    }
  },

  methods: {
    editar(d) {
      alert("Editar " + d.Equipo)
    },
    
    eliminar(d) {
      alert("Eliminar registro con código " + d['Numero Activo'])
    },

    openModal() {
      this.newDispositivo = {
        NumeroActivo: "",
        Equipo: "",
        Marca: "",
        Modelo: "",
        CodigoUbicacion: "",
        CodigoResponsable: ""
      }
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
    },

    guardarNuevoDispositivo() {
      if (!this.canSave) {
        alert("Completa todos los campos.")
        return
      }

      const nuevo = {
        "Numero Activo": this.newDispositivo.NumeroActivo,
        "Equipo": this.newDispositivo.Equipo,
        "Marca": this.newDispositivo.Marca,
        "Modelo": this.newDispositivo.Modelo,
        "Codigo Ubicacion": this.newDispositivo.CodigoUbicacion,
        "Codigo Responsable": this.newDispositivo.CodigoResponsable
      }

      this.dispositivos.push(nuevo)

      this.closeModal()
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

.search-meta {
  font-size: 0.95rem;
  color: #374151;
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