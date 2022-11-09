<template>
    <div class="SieveFilters">

        <!-- View Relation (Only for subsequent query blocks) -->

        <div v-if="queryState.payload.relation"
            class="-mx-2 -mt-2 lg:-mx-4 lg:-mt-4 relative p-1 lg:p-2"
        >
            <span class="font-bold text-sm">
                Must have relation: {{ getCurrentRelation().meta.name }}
            </span>

            <div v-if="queryState.payload.relation && getCurrentRelation().meta.describe"
                class="text-xs block mt-1" v-html="getCurrentRelation().meta.describe"
            ></div>

            <div class="absolute z-10 top-1/2 right-4" style="transform:translateY(-50%)">
                <a class="text-sm font-bold text-red-400" href="#"
                    @click.prevent="removeRelation(queryState.payload.relation)"
                >[x]</a>
            </div>
        </div>

        <!-- View Scopes -->

        <div v-if="queryState.payload.query.scopes.length > 0">
            <div v-for="scope in queryState.payload.query.scopes"
                class="relative border-t border-gray-200 p-2 lg:p-4 -mx-2 lg:-mx-4 text-sm"
            >
                Within scope: {{ schema[queryState.payload.model].scopes[scope].meta.name }}

                <div class="absolute z-10 right-4 top-1/2" style="transform:translateY(-50%);">
                    <a class="text-sm font-bold text-red-400" href="#"
                        @click.prevent="removeScope(scope)"
                    >[x]</a>
                </div>
            </div>
        </div>

        <!-- View attributes -->

        <div v-if="queryState.payload.query.attributes.length > 0">
            <div v-for="attribute, attributeKey in queryState.payload.query.attributes"
                class="relative border-t border-gray-200 p-2 lg:p-4 -mx-2 lg:-mx-4 text-sm"
            >
                Where: {{ attribute.field }}
                {{ getAttributeOperators()[attribute.operator].toLowerCase() }}
                <span v-if="!['null', 'notnull'].includes(attribute.operator)">{{ attribute.value }}</span>

                <div class="absolute z-10 right-4 top-1/2" style="transform:translateY(-50%);">
                    <a class="text-sm font-bold text-red-400" href="#"
                        @click.prevent="removeAttribute(attributeKey)"
                    >[x]</a>
                </div>
            </div>
        </div>

        <!-- Menu -->

        <div class="border-t border-gray-200 py-4 px-2 lg:px-4 -mx-2 lg:-mx-4">
            <label class="text-sm">Add filter:</label>

            <button type="button" class="rounded px-2 py-1 bg-blue-400 text-sm text-white mx-2"
                @click.prevent="state.showForm = 'scopes'" v-if="Object.keys(getScopes()).length > 0"
            >Scope</button>

            <button type="button" class="rounded px-2 py-1 bg-blue-400 text-sm text-white mx-2"
                @click.prevent="state.showForm = 'attributes'"
            >Attribute</button>

            <button type="button" class="rounded px-2 py-1 bg-blue-400 text-sm text-white mx-2"
                @click.prevent="state.showForm = 'relations'" v-if="Object.keys(getRelations()).length > 0"
            >Relation</button>
        </div>

        <!-- Form -->

        <div v-if="state.showForm"
            class="relative border-t border-gray-200 p-2 lg:p-4 -mx-2 lg:-mx-4">

            <div class="absolute -top-8 right-4">
                <a class="text-xs text-gray-400" href="#"
                    @click.prevent="state.showForm = null"
                >[close form]</a>
            </div>

            <!-- Form scopes -->

            <div v-if="state.showForm === 'scopes'">
                <div>
                    <label for="newScope" class="mr-2 lg:mr-4 text-sm">Select scope</label>
                    <select id="newScope" name="newScope" ref="newScope" @input="addScope">
                        <option value="">-- Please select --</option>
                        <option v-for="scope, key in getScopes()"
                            :value="key" v-html="scope.meta.name"
                        ></option>
                    </select>
                </div>
            </div>

            <!-- Form attributes -->

            <div v-if="state.showForm === 'attributes'" class="flex gap-2">
                <div class="flex-grow">
                    <label for="newAttributeField" class="block mr-2 lg:mr-4 text-sm">Select attribute</label>
                    <select id="newAttributeField" name="newAttributeField" ref="newAttributeField" v-model="state.attributeForm.field">
                        <option value="">-- Please select --</option>
                        <option v-for="attribute, key in getAttributes()"
                            :value="key" v-html="key"
                        ></option>
                    </select>
                </div>

                <div class="flex-grow">
                    <label for="newAttributeOperator" class="block mr-2 lg:mr-4 text-sm">Select operator</label>
                    <select id="newAttributeOperator" name="newAttributeOperator" ref="newAttributeOperator" v-model="state.attributeForm.operator">
                        <option value="">-- Please select --</option>
                        <option v-for="operator, key in getAttributeOperators()"
                            :value="key" v-html="operator"
                        ></option>
                    </select>
                </div>

                <div v-if="!['null', 'notnull'].includes(state.attributeForm.operator)" class="flex-grow">
                    <label for="newAttributeValue" class="block mr-2 lg:mr-4 text-sm">Enter value</label>
                    <input class="border border-gray-200" type="text" name="newAttributeValue" id="newAttributeValue" ref="newAttributeValue" v-model="state.attributeForm.value">
                </div>

                <div class="relative pt-5">
                    <button type="button" class="rounded bg-green-600 text-white px-4 py-2 leading-none"
                        @click.prevent="addAttribute">Add</button>
                </div>
            </div>

            <!-- Form relations -->

            <div v-if="state.showForm === 'relations'">
                <div>
                    <label for="newRelation" class="mr-2 lg:mr-4 text-sm">Select relation</label>
                    <select id="newRelation" name="newRelation" ref="newRelation" @input="addRelation">
                        <option value="">-- Please select --</option>
                        <option v-for="relation, key in getRelations()"
                            :value="key" v-html="relation.meta.name"
                        ></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({

    props: {

        queryState: {
            type: Object,
            required: true
        },

        schema: {
            type: Object,
            required: true
        }
    },

    data () {

        return {

            state: {

                showForm: null,

                attributeForm: {
                    field: '',
                    operator: '',
                    value: ''
                }
            }
        };
    },

    methods: {
        /**
         * Add an attribute requirement to the query object.
         *
         * @returns {void}
         * @var {function}
         */
        addAttribute () {

            let attributeForm = this.state.attributeForm;

            if (attributeForm.field === '') return;
            if (attributeForm.operator === '') return;
            if (attributeForm.value === '' && !['null', 'notnull'].includes(attributeForm.operator)) return;

            /* Update query state */
            this.$parent.state.payload.query.attributes.push(attributeForm);

            this.state.showForm = null;
            this.state.attributeForm = {
                field: '',
                operator: '',
                value: ''
            };
        },

        /**
         * Apply a scope requirement to the query object.
         *
         * @returns {void}
         * @var {function}
         */
        addScope () {

            let scope = this.$refs.newScope.value;
            if (scope === '') return;

            /* Update query state */
            this.$parent.state.payload.query.scopes.push(scope);

            this.state.showForm = null;
            this.$refs.newScope.value = '';
        },

        /**
         * Add a child sub-query object to the current query object. (That's recursion, baby!)
         *
         * @returns {void}
         * @var {function}
         */
        addRelation () {

            let relation = this.$refs.newRelation.value;
            if (relation === '') return;

            let relationConfig = this.schema[this.queryState.payload.model].relations[relation];

            /* Update query state */
            this.$parent.state.payload.query.relations.push(
                {

                    model: relationConfig.model,
                    relation,

                    query: {

                        attributes: [],
                        scopes: [],
                        relations: []
                    }
                }
            );

            this.state.showForm = null;
            this.$refs.newRelation.value = '';
        },

        /**
         * Get the arbitrary list of operators to work with the Sieve backend interface.
         *
         * @returns {object}
         * @var {function}
         */
        getAttributeOperators () {

            return {
                'eq': 'Equal to',
                'neq': 'Not equal to',
                'gt': 'Greater than',
                'gte': 'Greater than or equal to',
                'lt': 'Less than',
                'lte': 'Less than or equal to',
                'null': 'Is null',
                'notnull': 'Is not null'
            };
        },

        /**
         * Get the avaialble attributes for the selected model.
         *
         * @returns {object}
         * @var {function}
         */
        getAttributes () {
            /* Always return an object. ALWAYS. */
            if (Array.isArray(this.schema[this.queryState.payload.model].attributes)) {
                return {};
            }

            return this.schema[this.queryState.payload.model].attributes;
        },

        /**
         * Get the current relation meta used to create this filter.
         *
         * @return {object}
         * @var {function}
         */
        getCurrentRelation () {
            return this.schema[this.$parent.$parent.state.payload.model]
                .relations[this.queryState.payload.relation];
        },

        /**
         * Get the avaialble relations for the selected model, omitting relations already added
         * as sub-query objects, and not allowing the selection of relationships that utilize
         * models ignored in discovery.
         *
         * @returns {object}
         * @var {function}
         */
        getRelations () {
            /* Always return an object. ALWAYS. */
            if (Array.isArray(this.schema[this.queryState.payload.model].relations)) {
                return {};
            }

            var SieveQuery = this;

            return _.omitBy(
                this.schema[this.queryState.payload.model].relations,
                (config, relation) => {
                    return _.map(SieveQuery.queryState.payload.query.relations, 'relation').includes(relation)
                        || !Object.keys(this.schema).includes(config.model);
                }
            )
        },

        /**
         * Get the scopes available for the current model context - this will hide scopes already applied.
         *
         * @returns {object}
         * @var {function}
         */
        getScopes () {

            if (Array.isArray(this.schema[this.queryState.payload.model].scopes)) {
                return {};
            }

            var SieveQuery = this;

            return _.omitBy(
                this.schema[this.queryState.payload.model].scopes,
                (config, scope) => {
                    return SieveQuery.queryState.payload.query.scopes.includes(scope);
                }
            )
        },

        /**
         * Remove an attribute from the current query object.
         *
         * @param {string} attributeKey
         *
         * @returns {void}
         * @var {function}
         */
        removeAttribute (attributeKey) {

            if (this.queryState.payload.query.attributes.length <= 1) {
                this.queryState.payload.query.attributes = [];
                return;
            }

            this.queryState.payload.query.attributes =
                this.queryState.payload.query.attributes.splice(attributeKey, 1);
        },

        /**
         * Remove a relation sub-query from the current query object.
         *
         * @param {string} relation
         *
         * @returns {void}
         * @var {function}
         */
        removeRelation (relation) {

            this.$parent.$parent.state.payload.query.relations = _.filter(
                this.$parent.$parent.state.payload.query.relations,
                (value) => {
                    return value.relation !== relation;
                }
            )
        },

        /**
         * Remove a scope from the current query object.
         *
         * @param {string} scope
         *
         * @returns {void}
         * @var {function}
         */
        removeScope (scope) {

            _.remove(
                this.queryState.payload.query.scopes,
                (value) => {
                    return value === scope;
                }
            )
        }
    }
})
</script>
