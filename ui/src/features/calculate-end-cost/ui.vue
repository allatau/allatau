<template>
    <div style="height: 100px;">
        <div style="display: flex;flex-direction: column; gap: 6px;min-width: 300px;margin-top: 6px">
            <h2>Расчет конечной стоимости</h2>
            <div style="display: flex; flex-direction: column; gap: 6px; max-width: 600px;">

                <a-row :gutter="12">
                    <a-col :span="6">
                        <a-input placeholder="Z" v-model:value="Z" style="min-width: 90px;" />
                    </a-col>
                    <a-col :span="6">
                        <a-input placeholder="D" v-model:value="D" style="min-width: 90px;" />
                    </a-col>

                </a-row>
                <a-row :gutter="12">

                    <a-col :span="6">
                        <a-input placeholder="N" v-model:value="N" style="min-width: 90px;" />
                    </a-col>
                    <a-col :span="6">
                        <a-input placeholder="M" v-model:value="M" style="min-width: 90px;" />
                    </a-col>
                </a-row>
                <a-row :gutter="12">

                    <a-col :span="12">
                        <a-input placeholder="F" v-model:value="F" style="min-width: 90px;" />
                    </a-col>
                </a-row>
            </div>


            <div style="display: flex; align-items: center; ">
                <a target="popup" :onclick="`window.open('${link}','name','width=800,height=600')`">
                    <a-button>Рассчитать</a-button>
                </a>
            </div>

        </div>
    </div>
</template>
  
<script lang="ts">
import { defineComponent, toRef, ref, computed, watch } from 'vue';

// import { DebitDebtsList } from "~/src/widgets/debit-debts-list"

// import { DebtModel } from "~/src/entities/debt"
// import { useLoaderState } from "~/src/shared/lib"

export default defineComponent({
    props: {
        Z: {
            type: Number,
            default: 1000
        },
        D: {
            type: Number,
            default: 250
        },
        N: {
            type: Number,
            default: 0.2
        },
        M: {
            type: Number,
            default: 0.15
        },
        F: {
            type: Number,
            default: 0.16666666666666666
        },
    },
    components: {

    },
    setup(props) {

        const Z = ref<number>(props.Z)
        const D = ref<number>(props.D)
        const N = ref<number>(props.N)
        const M = ref<number>(props.M)
        const F = ref<number>(props.F)

        const link = computed(() => {
            return `/end-cost-calculator?Z=${Z.value}&D=${D.value}&N=${N.value}&M=${M.value}&F=${F.value}`
        })

        watch(() => props.Z, (val, prevVal) => {
            Z.value = val
        })

        watch(() => props.D, (val, prevVal) => {
            D.value = val
        })

        watch(() => props.N, (val, prevVal) => {
            N.value = val
        })

        watch(() => props.M, (val, prevVal) => {
            M.value = val
        })

        watch(() => props.F, (val, prevVal) => {
            F.value = val
        })

        return {
            link,
            Z,
            D,
            N,
            M,
            F

        };
    }
});
</script>
  