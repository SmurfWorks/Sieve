<template>
    <div class="Sieve">
        <article class="p-4 lg:p-8 border-b border-gray-200 bg-white">
            <div class="flex w-full">
                <div class="flex-grow">
                    <h1 class="text-xl font-light">Sieve</h1>
                    <p class="text-xs">
                        Quickly and easily browse the data of your Laravel application using app-layer logic.
                        <span class="block font-bold text-blue-500">By <a class="underline" href="https://www.smurfworks.com" target="_blank">SmurfWorks</a></span>
                    </p>
                </div>

                <div class="text-right pl-4">
                    <button type="button" @click.prevent="submit"
                        v-if="state.view === 'form'"
                        class="align-middle inline-block py-2 px-4 rounded bg-green-600 shadow-sm text-white text-sm lg:text-base text-center"
                    >Run query</button>

                    <button type="button" @click.prevent="this.state.view = 'form';this.state.query = null;"
                        v-if="state.view === 'results'"
                        class="align-middle inline-block py-2 px-4 rounded bg-blue-500 shadow-sm text-white text-sm lg:text-base text-center"
                    >Change query</button>
                </div>
            </div>
        </article>

        <div class="m-4 lg:m-8">
            <form v-show="state.view === 'form'"
                ref="queryForm" @submit.prevent="submit">

                <SieveQuery
                    :schema="schema"
                    :payload="state.payload"
                    @sieve-update="updatePayload"
                />
            </form>

            <div v-show="state.view === 'loading'"
                class="text-center text-sm font-bold text-gray-500">
                Loading...
            </div>

            <div v-show="state.view === 'results'">
                <SieveResults v-if="state.results.page"
                    :schema="schema"
                    :query="state.payload"
                    :page="state.results.page"
                />
            </div>

            <pre v-if="state.debug && state.results.sqlQuery" class="p-2 lg:p-4 font-mono text-xs mb-4"
                v-html="state.results.sqlQuery"
            ></pre>

            <pre v-if="state.debug" class="p-2 lg:p-4 font-mono text-xs"
                v-html="JSON.stringify(state.payload, null, '\t')"
            ></pre>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import SieveQuery from './SieveQuery.vue';
import SieveResults from './SieveResults.vue';

export default defineComponent({

    components: {
        SieveQuery,
        SieveResults
    },

    props: {

        schema: {
            type: Object,
            required: true
        }
    },

    data () {

        return {

            state: {

                debug: false,
                view: 'form',

                errors: {},
                payload: {

                    model: '',
                    relation: false,
                    query: {

                        attributes: [],
                        scopes: [],
                        relations: []
                    }
                },

                results: {
                    sqlQuery: null,
                    page: null
                }
            }
        };
    },

    methods: {
        /**
         * Update the payload from child query block changes.
         *
         * @param {object} payload
         *
         * @returns {void}
         * @var {function}
         */
        updatePayload (payload) {
            this.state.payload = payload;
        },

        /**
         * Submit the generated query to the result endpoint.
         *
         * @returns {void}
         * @var {function}
         */
        submit () {

            this.state.results.page = null;
            this.state.results.sqlQuery = null;

            if (this.state.payload.model === '') {
                return;
            }

            this.state.view = 'loading';

            axios.post(
                '/sieve/a/result',
                { query: this.state.payload }
            ).then(
                (response) => {

                    this.state.view = 'results';

                    this.state.results.page = response.data.data.page;
                    this.state.results.sqlQuery = response.data.data.query;
                }
            ).catch(
                (error) => {

                }
            )
        }
    }
})
</script>
