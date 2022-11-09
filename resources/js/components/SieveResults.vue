<template>
    <div>
        <h1 class="text-2xl font-light mb-4">Query results</h1>

        <div class="bg-white shadow-sm w-full overflow-auto">
            <table>
                <thead>
                    <tr>
                        <th v-for="key in Object.keys(schema[query.model].attributes)"
                            class="p-4 border-b border-gray-100">{{ key }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="row in page.data">

                        <td v-for="key in Object.keys(schema[query.model].attributes)"
                            class="p-4 whitespace-nowrap text-ellipsis overflow-hidden"
                            style="max-width:200px"
                            v-html="format(key, row[key])"
                        ></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({

    props: {

        schema: {
            type: Object,
            required: true
        },

        query: {
            type: Object,
            required: true
        },

        page: {
            type: Object,
            required: true
        }
    },

    data () {

        return {

            state: {}
        };
    },

    methods: {
        /**
         * Determine whether the given attribute is hidden.
         *
         * @param {string} key
         *
         * @returns {bool}
         * @var {function}
         */
        isHidden (key) {
            return this.schema[this.query.model].attributes[key].hidden;
        },

        /**
         * Apply conditional formatting to given results.
         *
         * @param {string} key
         * @param {mixed} value
         *
         * @returns {string}
         * @var {function}
         */
        format (key, value) {

            if (value === null) {
                return '<span class="text-gray-500 italic text-sm">null</span>';
            }

            return String(value);
        }
    }
})
</script>
