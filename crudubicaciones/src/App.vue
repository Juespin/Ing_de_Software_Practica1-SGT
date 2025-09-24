<template>
  <div class="app">
    <header class="header">
      <h1>📋 Sistema de Gestión Tecnológica (SGT)</h1>
      <p>Gestión de ubicaciones, personal responsable y equipos médicos del centro de salud</p>
    </header>

    <!-- Barra de pestañas -->
    <div class="tabs">
      <button 
        :class="{ active: activeTab === 'ubicaciones' }" 
        @click="activeTab = 'ubicaciones'">
        🏥 Ubicaciones
      </button>
      <button 
        :class="{ active: activeTab === 'responsables' }" 
        @click="activeTab = 'responsables'">
        👨‍⚕️ Responsables
      </button>
      <button 
        :class="{ active: activeTab === 'equipos' }" 
        @click="activeTab = 'equipos'">
        🩺 Equipos Médicos
      </button>
    </div>

    <!-- Contenido dinámico -->
    <div class="tab-content">
      <Ubicaciones v-if="activeTab === 'ubicaciones'" />
      <Responsables v-if="activeTab === 'responsables'" />
      <EquiposMedicos v-if="activeTab === 'equipos'" />
    </div>
  </div>
</template>

<script>
import Ubicaciones from "./components/Ubicaciones.vue";
import Responsables from "./components/Responsables.vue";
import EquiposMedicos from "./components/Equiposmedicos.vue";

export default {
  components: { Ubicaciones, Responsables, EquiposMedicos },
  data() {
    return {
      activeTab: "ubicaciones"
    };
  }
};
</script>

<style scoped>
/* Encabezado */
.header {
  padding: 16px;
  background: #f8fbff;
  border-radius: 10px;
  margin-bottom: 20px;
}
.header h1 {
  font-size: 20px;
  margin: 0;
}
.header p {
  color: #666;
  margin: 5px 0 0;
}

/* Pestañas */
.tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}
.tabs button {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  background: #e8f0ff;
  cursor: pointer;
  font-size: 16px;
  font-weight: 500;
}
.tabs button.active {
  background: #3b82f6;
  color: white;
}
.tab-content {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>
