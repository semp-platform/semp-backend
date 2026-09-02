document.addEventListener('DOMContentLoaded', () => {

    const electionType = document.getElementById('election_type_id');
    const state = document.getElementById('state_id');

    const lgaContainer = document.getElementById('lga-container');
    const wardContainer = document.getElementById('ward-container');
    const lcdaContainer = document.getElementById('lcda-container');
    const lcdaWardContainer = document.getElementById('lcda-ward-container');

    const lga = document.getElementById('lga_id');
    const ward = document.getElementById('ward_id');
    const lcda = document.getElementById('lcda_id');
    const lcdaWard = document.getElementById('lcda_ward_id');

    const form = document.querySelector('form');

    const selectedLga = form?.dataset.lga || '';
    const selectedWard = form?.dataset.ward || '';
    const selectedLcda = form?.dataset.lcda || '';
    const selectedLcdaWard = form?.dataset.lcdaWard || '';

    function hideAll() {

        lgaContainer.classList.add('hidden');
        wardContainer.classList.add('hidden');
        lcdaContainer.classList.add('hidden');
        lcdaWardContainer.classList.add('hidden');

        lga.innerHTML =
            '<option value="">Select LGA</option>';

        ward.innerHTML =
            '<option value="">Select Ward</option>';

        lcda.innerHTML =
            '<option value="">Select LCDA</option>';

        lcdaWard.innerHTML =
            '<option value="">Select LCDA Ward</option>';
    }

    function loadLgas(stateId) {

        if (!stateId) {
            return;
        }

        fetch(`/locations/states/${stateId}/lgas`)
            .then(response => response.json())
            .then(items => {

                lga.innerHTML =
                    '<option value="">Select LGA</option>';

                items.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;
                    option.textContent = item.name;

                    if (String(item.id) === selectedLga) {
                        option.selected = true;
                    }

                    lga.appendChild(option);
                });

                if (selectedLga) {
                    loadLocationAfterLga();
                }
            });
    }

    function loadWards(lgaId) {

        if (!lgaId) {
            return;
        }

        fetch(`/locations/lgas/${lgaId}/wards`)
            .then(response => response.json())
            .then(items => {

                ward.innerHTML =
                    '<option value="">Select Ward</option>';

                items.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;
                    option.textContent = item.name;

                    if (String(item.id) === selectedWard) {
                        option.selected = true;
                    }

                    ward.appendChild(option);
                });
            });
    }

    function loadLcdas(lgaId) {

        if (!lgaId) {
            return;
        }

        fetch(`/locations/lgas/${lgaId}/lcdas`)
            .then(response => response.json())
            .then(items => {

                lcda.innerHTML =
                    '<option value="">Select LCDA</option>';

                items.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;
                    option.textContent = item.name;

                    if (String(item.id) === selectedLcda) {
                        option.selected = true;
                    }

                    lcda.appendChild(option);
                });

                if (selectedLcda && selectedLcdaWard) {
                    loadLcdaWards(selectedLcda);
                }
            });
    }

    function loadLcdaWards(lcdaId) {

        if (!lcdaId) {
            return;
        }

        fetch(`/locations/lcdas/${lcdaId}/wards`)
            .then(response => response.json())
            .then(items => {

                lcdaWard.innerHTML =
                    '<option value="">Select LCDA Ward</option>';

                items.forEach(item => {

                    const option =
                        document.createElement('option');

                    option.value = item.id;
                    option.textContent = item.name;

                    if (String(item.id) === selectedLcdaWard) {
                        option.selected = true;
                    }

                    lcdaWard.appendChild(option);
                });
            });
    }

    function loadLocationAfterLga() {

        const text =
            electionType.options[electionType.selectedIndex].text;

        if (
            text === 'Bye Election' ||
            text === 'Re-run Election' ||
            text === 'Supplementary Election'
        ) {

            loadWards(selectedLga);

        }

        if (
            text === 'LCDA Election' ||
            text === 'LGA/LCDA Election'
        ) {

            loadLcdas(selectedLga);

        }

        if (text === 'LCDA Bye Election') {

            loadLcdas(selectedLga);

        }
    }

    electionType.addEventListener('change', () => {

        hideAll();

        const text =
            electionType.options[electionType.selectedIndex].text;

        if (text === 'Local Government Election') {
    return;
}

        if (
            text === 'Bye Election' ||
            text === 'Re-run Election' ||
            text === 'Supplementary Election'
        ) {

            lgaContainer.classList.remove('hidden');
            wardContainer.classList.remove('hidden');

            if (state.value) {
                loadLgas(state.value);
            }

            return;
        }

        if (text === 'LCDA Election') {
    return;
}

if (text === 'LGA/LCDA Election') {
    return;
}

        if (text === 'LCDA Bye Election') {

            lgaContainer.classList.remove('hidden');
            lcdaContainer.classList.remove('hidden');
            lcdaWardContainer.classList.remove('hidden');

            if (state.value) {
                loadLgas(state.value);
            }
        }
    });

    state.addEventListener('change', () => {

        if (!state.value) {
            hideAll();
            return;
        }

        const text =
            electionType.options[electionType.selectedIndex].text;

        lga.innerHTML =
            '<option value="">Select LGA</option>';

        ward.innerHTML =
            '<option value="">Select Ward</option>';

        lcda.innerHTML =
            '<option value="">Select LCDA</option>';

        lcdaWard.innerHTML =
            '<option value="">Select LCDA Ward</option>';

    if (
    text === 'Bye Election' ||
    text === 'Re-run Election' ||
    text === 'Supplementary Election' ||
    text === 'LCDA Bye Election'
) {

    loadLgas(state.value);
}
    });

    lga.addEventListener('change', () => {

        const text =
            electionType.options[electionType.selectedIndex].text;

        ward.innerHTML =
            '<option value="">Select Ward</option>';

        lcda.innerHTML =
            '<option value="">Select LCDA</option>';

        lcdaWard.innerHTML =
            '<option value="">Select LCDA Ward</option>';

        if (
            text === 'Bye Election' ||
            text === 'Re-run Election' ||
            text === 'Supplementary Election'
        ) {

            loadWards(lga.value);
        }

        if (text === 'LCDA Bye Election') {

    loadLcdas(lga.value);
}
    });

    lcda.addEventListener('change', () => {

        lcdaWard.innerHTML =
            '<option value="">Select LCDA Ward</option>';

        const text =
            electionType.options[electionType.selectedIndex].text;

        if (
            text === 'LCDA Bye Election' &&
            lcda.value
        ) {

            loadLcdaWards(lcda.value);
        }
    });

    // Initialize when editing an election
    if (electionType.value) {
        electionType.dispatchEvent(new Event('change'));
    }

});
