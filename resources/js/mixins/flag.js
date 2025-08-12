export default {
    methods: {
        flagSrc(sovereignState) {
            const code = sovereignState.code_2.toLowerCase();
            return this.asset('images/flags/'+code+'.svg');
        },
    }
}
