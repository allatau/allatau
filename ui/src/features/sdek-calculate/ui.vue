<template>
    <div style="height: 100px;">
        <div style="display: flex;flex-direction: column; gap: 6px;min-width: 300px;margin-top: 6px">
            <h2>Расчет доставки</h2>
            <div style="display: flex; flex-direction: column; gap: 6px; max-width: 600px;">

                <a-row :gutter="12">
                    <a-col>
                        <a-input v-model:value.lazy="fromLocation" placeholder="Город отправления" />
                    </a-col>
                    <a-col>
                        <a-input v-model:value.lazy="toLocation" placeholder="Город получения" />
                    </a-col>
                </a-row>

                <a-row :gutter="12">
                    <a-col :span="3">
                        <a-input placeholder="width" v-model:value="width" />
                    </a-col>
                    <a-col :span="3">
                        <a-input placeholder="height" v-model:value="height" />
                    </a-col>
                    <a-col :span="3">
                        <a-input placeholder="length" v-model:value="length" />
                    </a-col>
                    <a-col :span="3">
                        <a-input placeholder="weight" v-model:value="weight" />
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
        fromLocation: {
            type: String,
            default: "Москва"
        },
        toLocation: {
            type: String,
            default: "Екатеринбург"
        },
        length: {
            type: Number,
            default: 10
        },
        width: {
            type: Number,
            default: 6
        },
        height: {
            type: Number,
            default: 8
        },
        weight: {
            type: Number,
            default: 1
        },
    },
    components: {

    },
    setup(props) {
        const fromLocation = ref<string>(props.fromLocation)
        const toLocation = ref<string>(props.toLocation)

        const length = ref<number>(props.length)
        const width = ref<number>(props.width)
        const height = ref<number>(props.height)
        const weight = ref<number>(props.weight)

        const link = computed(() => {
            return `https://site.ru/?from_location=${fromLocation.value}&to_location=${toLocation.value}&length=${length.value}&width=${width.value}&height=${height.value}&weight=${weight.value}`
        })

        watch(() => props.length, (val, prevVal) => {
            length.value = val
        })

        watch(() => props.width, (val, prevVal) => {
            width.value = val
        })

        watch(() => props.height, (val, prevVal) => {
            height.value = val
        })

        watch(() => props.weight, (val, prevVal) => {
            weight.value = val
        })


        return {
            fromLocation,
            toLocation,
            link,
            length,
            width,
            height,
            weight

        };
    }
});
</script>
  