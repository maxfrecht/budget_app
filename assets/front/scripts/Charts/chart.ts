import Chart from 'chart.js/auto';

export function initChart() {
    let ctx: CanvasRenderingContext2D = document.querySelector<HTMLCanvasElement>('#chart').getContext('2d');
    let paiements: NodeListOf<HTMLElement> = document.querySelectorAll('.paiements li');
    let labels: Array<any> = [];
    let amounts: Number[] = [];
    let backgroundColor = [];
    let borderColor = [];
    let labelsAndAmounts = [];
    paiements.forEach((p, k) => {
        labelsAndAmounts.push({category: p.dataset.category, amount: p.dataset.amount});
        backgroundColor.push(`rgba( 50, ${25*k}, 100, 0.5)`);
        borderColor.push('rgb(173,220,184)')
    })
    labelsAndAmounts = groupBy(labelsAndAmounts, 'category')
    for(const [category, array] of Object.entries(labelsAndAmounts)) {
            let amount = 0;
            array.forEach(p => {
                amount += parseInt(p.amount);
            })
        amounts.push(amount)
        labels.push(category);
    }
    console.log(labels);
    let myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            // @ts-ignore
            datasets: [{
                label: 'Répartition des dépenses',
                data: amounts,
                backgroundColor: backgroundColor,
                hoverOffset: 25,
                borderColor: borderColor,
                borderWidth: 3
            }],
        },
        options: {
            layout: {
              padding: 25
            },
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "right",
                    labels: {
                        color: "#f1f1f1",
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 16
                        }
                    }
                }
            }
        }
    });
}


function groupBy(tableau, propriete) {
    return tableau.reduce((acc, curr) => {
        const cle = curr[propriete];
        if (!acc[cle]) {
            acc[cle] = [];
        }
        acc[cle].push(curr);
        return acc;
    }, {});
}