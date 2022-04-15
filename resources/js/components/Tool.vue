<template>
    <div>
        <div class="flex">
            <div class="w-1/2">
                <heading class="mb-6">Checkout</heading>

                <card
                    class="bg-10 flex flex-col justify-center items-center"
                >
                    <loading-view :loading="branchLoading" class="w-full">

                        <div class="w-auto border-b border-40">
                            <div class="flex py-6 px-8">
                                <label
                                    :for="branch"
                                    class="inline-block text-80 pt-2 leading-tight"
                                >
                                    <h4>Branch</h4>
                                </label>

                                <select-control
                                    :id="branch"
                                    @change="handleCheckoutChange"
                                    class="grow form-control form-select ml-4 mr-4"
                                >
                                    <option value="" :disabled="true">
                                        Please select a branch
                                    </option>

                                    <option v-for="branch in branches" :selected="index === 0" :value="branch">
                                        {{ branch }}
                                    </option>
                                </select-control>

                                <button
                                    type="submit"
                                    @click="checkout"
                                    class="btn btn-default btn-primary ml-2"
                                >
                                    Checkout
                                </button>
                            </div>

                            <div class="flex pb-6 px-8">
                                Result:&nbsp;&nbsp;
                                <span
                                    :class="(checkoutResult.success) ? 'text-success' : 'text-danger'">
                                    {{ checkoutResult.message }}
                                </span>
                            </div>
                        </div>
                    </loading-view>
                </card>
            </div>

            <div class="w-1/2 ml-4">
                <heading class="mb-6">Pull</heading>

                <card
                    class="bg-10 flex flex-col justify-center items-center"
                >
                    <loading-view :loading="branchLoading" class="w-full">

                        <div class="w-auto border-b border-40">
                            <div class="flex py-6 px-8">
                                <label
                                    :for="branch"
                                    class="inline-block text-80 pt-2 leading-tight"
                                >
                                    <h4>Branch</h4>
                                </label>

                                <select-control
                                    :id="branch"
                                    @change="handlePullChange"
                                    class="grow form-control form-select ml-4 mr-4"
                                >
                                    <option value="" :disabled="true">
                                        Please select a branch
                                    </option>

                                    <option v-for="branch in branches" :selected="index === 0" :value="branch">
                                        {{ branch }}
                                    </option>
                                </select-control>

                                <button
                                    type="submit"
                                    @click="pull"
                                    class="btn btn-default btn-primary ml-2"
                                >
                                    Pull
                                </button>
                            </div>

                            <div class="flex pb-6 px-8">
                                Result:&nbsp;&nbsp;
                                <span
                                    :class="(pullResult.success) ? 'text-success' : 'text-danger'">
                                    {{ pullResult.message }}
                                </span>
                            </div>
                        </div>
                    </loading-view>
                </card>
            </div>
        </div>

        <div>
            <div class="flex items-center">
                <heading class="mb-6 mt-6">Git Graph</heading>

                <div class="flex items-center ml-auto">
                    <button
                        dusk="open-delete-modal-button"
                        class="btn btn-default btn-icon btn-white mr-3"
                        style="padding-left: 0.75rem; padding-right: 0.95rem;"
                        @click="refreshGraph"
                        :title="__('Refresh')"
                    >
                        <icon type="refresh" class="text-80"/>
                    </button>
                </div>
            </div>

            <card
                class="bg-10 flex flex-col items-center justify-center"
                style="min-height: 300px;"
            >
                <loading-view :loading="graphLoading">
                </loading-view>

                <div class="network-graph" style="width: 100%;"></div>
            </card>
        </div>
    </div>
</template>

<script>

import Network from "../network";

export default {
    metaInfo() {
        return {
            title: 'Nova Git Manager',
        }
    },
    mounted() {
        this.initBranches();
        this.initGraph();
    },
    data: () => ({
        branches: [],
        graph: null,
        graphLoading: true,
        branchLoading: true,
        pullSelectedBranch: '',
        checkoutSelectedBranch: '',
        checkoutResult: '',
        pullResult: ''
    }),
    methods: {
        initBranches() {
            Nova.request().get('/nova-vendor/nova-git-manager/branches').then(response => {
                this.branchLoading = false;

                let res = response.data;
                this.branches = res.branches;
            });
        },

        initGraph() {
            Nova.request().get('/nova-vendor/nova-git-manager/log').then(response => {
                this.graphLoading = false;

                let res = response.data;
                this.graph = new Network({
                    url: res.data_url,
                    commit_url: res.commit_url
                });
            });
        },

        refreshGraph() {
            this.graphLoading = true;
            this.graph.clear();
            this.initGraph();
        },

        handlePullChange(e) {
            this.pullSelectedBranch = e.target.value

            if (this.field) {
                Nova.$emit(this.field.attribute + '-change', this.value)
            }
        },

        handleCheckoutChange(e) {
            this.checkoutSelectedBranch = e.target.value

            if (this.field) {
                Nova.$emit(this.field.attribute + '-change', this.value)
            }
        },

        pull() {
            if (this.pullSelectedBranch.length <= 0) {
                return;
            }

            Nova.request().post('/nova-vendor/nova-git-manager/pull', {branch: this.pullSelectedBranch}).then(response => {
                this.pullResult = response.data;
                this.refreshGraph();
            });
        },

        checkout() {
            if (this.checkoutSelectedBranch.length <= 0) {
                return;
            }

            Nova.request().post('/nova-vendor/nova-git-manager/checkout', {branch: this.checkoutSelectedBranch}).then(response => {
                this.checkoutResult = response.data;
                this.refreshGraph();
            });
        }
    }
}
</script>

<style>
.grow {
    flex-grow: 1;
}

.network-graph {
    width: 100%;
    margin-right: auto;
    overflow-y: scroll;
    overflow-x: hidden;
}

.network-graph svg {
    margin-bottom: -4px; /* Remove whitespace at the bottom of the page. */
}
</style>
