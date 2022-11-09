<template>
    <div class="SieveQuery">
        <div class="inline-block bg-white shadow-sm pt-2 lg:pt-4 px-2 lg:px-4 pb-0 mb-2 lg:mb-4" style="width: 720px">

            <!-- Initial model selector -->

            <div v-if="state.payload.relation === false" class="mb-2 lg:mb-4">
                <label for="model" class="block text-sm mb-1 lg:mb-2">Select model to query</label>
                <select id="model" name="model" ref="setModel" @input="setModel">
                    <option value="">-- Please select --</option>
                    <option v-for="model in Object.keys(schema)"
                        :value="model" v-html="schema[model].meta.name"
                    ></option>
                </select>
            </div>

            <SieveFilters
                v-if="state.payload.model"
                :schema="schema"
                :query-state="state"
            />
        </div>

        <div class="pl-8 lg:pl-16"
            v-for="relation in state.payload.query.relations">

            <SieveQuery
                :schema="schema"
                :payload="relation"
                @sieve-update="updatePayload(relation.relation, ...arguments)"
            />
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import SieveFilters from './SieveFilters.vue';

export default defineComponent({

    components: {
        SieveFilters
    },

    props: {

        schema: {
            type: Object,
            required: true
        },

        payload: {
            type: Object,
            required: true
        }
    },

    data () {

        return {

            state: {
                payload: this.payload
            }
        };
    },

    watch: {

        'state.payload': {
            deep: true,
            handler () {
                this.$emit('sieve-update', this.state.payload);
            }
        }
    },

    methods: {
        /**
         * Update this payload with a child query-block's data.
         *
         * @param {string} relationName
         * @param {object} payload
         *
         * @returns {void}
         * @var {function}
         */
        updatePayload (relationName, payload) {
            this.state.payload.query.relations[relationName] = payload;
        },

        /**
         * Set the model of this query block when this query block is not a relation (i.e. the first
         * query block in the query object).
         *
         * @returns {void}
         * @var {function}
         */
        setModel () {

            let model = this.$refs.setModel.value;

            this.state.payload = {

                model,
                relation: false,

                query: {

                    attributes: [],
                    scopes: [],
                    relations: []
                }
            };
        }
    }
})
</script>
