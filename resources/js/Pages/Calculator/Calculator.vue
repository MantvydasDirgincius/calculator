<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";

const page = usePage();
const logList = ref("");
const current = ref("");
const answer = ref("");
const operatorClicked = ref(true);
const history = ref(page.props.previous_calculations);
const form = useForm({
    numbers: "",
    operators: "",
});

onMounted(async () => {
    window.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeydown);
});
const equal = async () => {
    animateOperator("equal");

    if (!operatorClicked.value) {
        try {
            const numbers = logList.value
                .split(" ")
                .filter(
                    (item, index) => index % 2 === 0 && !isNaN(parseFloat(item))
                )
                .map((num) => parseFloat(num));

            if (current.value !== "") {
                numbers.push(parseFloat(current.value));
            }

            const operators = logList.value
                .split(" ")
                .filter(
                    (item, index) =>
                        index % 2 !== 0 && ["+", "-", "*", "/"].includes(item)
                );

            if (numbers.length - 1 !== operators.length) {
                answer.value = "Invalid Input: Operators and Numbers mismatch.";
                return;
            }

            form.numbers = numbers;
            form.operators = operators;
            form.post(route("calculator.calculate"), {
                onSuccess: (page) => {
                    answer.value = parseFloat(
                        page.props.flash.processData.result.toFixed(6)
                    );
                    logList.value = "";
                    current.value = "";
                    operatorClicked.value = true;
                    history.value =
                        page.props.flash.processData.previous_calculations;
                },
            });
        } catch (error) {
            answer.value = "Error";
            console.error("Calculation failed:", error);
        }
    } else {
        answer.value = "Error";
    }
};
const append = (number) => {
    answer.value = "";
    if (operatorClicked.value) {
        current.value = "";
        operatorClicked.value = false;
    }
    animateNumber(`n${number}`);
    current.value = `${current.value}${number}`;
};

const addtoLog = (operator) => {
    if (!operatorClicked.value) {
        logList.value += `${current.value} ${operator} `;
        current.value = "";
        operatorClicked.value = true;
    }
};

const animateNumber = (number) => {
    const element = document.getElementById(number);
    if (element) {
        element.style.backgroundColor = "#c1e3ff";
        setTimeout(() => {
            element.style.backgroundColor = "#f4faff";
        }, 250);
    }
};

const animateOperator = (operator) => {
    const element = document.getElementById(operator);
    if (element) {
        element.style.backgroundColor = "#a6daff";
        setTimeout(() => {
            element.style.backgroundColor = "#d9efff";
        }, 250);
    }
};

const clear = () => {
    animateOperator("clear");
    current.value = "";
    answer.value = "";
    logList.value = "";
    operatorClicked.value = false;
};

const sign = () => {
    animateOperator("sign");
    if (current.value !== "") {
        current.value =
            current.value.charAt(0) === "-"
                ? current.value.slice(1)
                : `-${current.value}`;
    }
};

const percent = () => {
    animateOperator("percent");
    if (current.value !== "") {
        current.value = `${parseFloat(current.value) / 100}`;
    }
};

const dot = () => {
    animateNumber("dot");
    if (!current.value.includes(".")) {
        append(".");
    }
};

const divide = () => {
    animateOperator("divide");
    addtoLog("/");
};

const times = () => {
    animateOperator("times");
    addtoLog("*");
};

const minus = () => {
    animateOperator("minus");
    addtoLog("-");
};

const plus = () => {
    animateOperator("plus");
    addtoLog("+");
};

const formatExpression = (numbers, operations) => {
    const nums = typeof numbers === "string" ? JSON.parse(numbers) : numbers;
    const ops = operations.split(" ");

    let expression = nums[0].toString();
    for (let i = 0; i < ops.length; i++) {
        expression += ` ${ops[i]} ${nums[i + 1]}`;
    }
    return expression;
};

const handleKeydown = (event) => {
    const key = event.key;

    if (!isNaN(key)) {
        append(key);
    }

    if (["+", "-", "*", "/"].includes(key)) {
        addtoLog(key);
    }

    if (key === ".") {
        dot();
    }

    if (key === "Enter") {
        equal();
    }

    if (key === "Escape") {
        clear();
    }

    if (key === "Backspace") {
        current.value = current.value.slice(0, -1);
    }
};
</script>

<template>
    <Head title="Calculator" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Calculator
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="calculator">
                        <div class="answer">{{ answer }}</div>
                        <div class="display">{{ logList + current }}</div>
                        <div @click="clear" id="clear" class="btn operator">
                            C
                        </div>
                        <div @click="sign" id="sign" class="btn operator">
                            +/-
                        </div>
                        <div @click="percent" id="percent" class="btn operator">
                            %
                        </div>
                        <div @click="divide" id="divide" class="btn operator">
                            /
                        </div>
                        <div @click="append('7')" id="n7" class="btn">7</div>
                        <div @click="append('8')" id="n8" class="btn">8</div>
                        <div @click="append('9')" id="n9" class="btn">9</div>
                        <div @click="times" id="times" class="btn operator">
                            *
                        </div>
                        <div @click="append('4')" id="n4" class="btn">4</div>
                        <div @click="append('5')" id="n5" class="btn">5</div>
                        <div @click="append('6')" id="n6" class="btn">6</div>
                        <div @click="minus" id="minus" class="btn operator">
                            -
                        </div>
                        <div @click="append('1')" id="n1" class="btn">1</div>
                        <div @click="append('2')" id="n2" class="btn">2</div>
                        <div @click="append('3')" id="n3" class="btn">3</div>
                        <div @click="plus" id="plus" class="btn operator">
                            +
                        </div>
                        <div @click="append('0')" id="n0" class="zero">0</div>
                        <div @click="dot" id="dot" class="btn">.</div>
                        <div @click="equal" id="equal" class="btn operator">
                            =
                        </div>
                    </div>
                </div>
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg history"
                >
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 mb-4"
                    >
                        History
                    </h2>
                    <div class="history-grid">
                        <div
                            v-for="calc in history"
                            :key="calc.id"
                            class="history-item"
                        >
                            <div class="history-expression">
                                {{
                                    formatExpression(
                                        calc.numbers,
                                        calc.operation
                                    )
                                }}
                            </div>
                            <div class="history-result">
                                = {{ parseFloat(calc.result) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.calculator {
    display: grid;
    grid-template-rows: repeat(7, minmax(60px, auto));
    grid-template-columns: repeat(4, 60px);
    grid-gap: 12px;
    padding: 35px;
    font-family: "Poppins";
    font-weight: 300;
    font-size: 18px;
    background-color: #ffffff;
    border-radius: 10px;
    justify-content: center;
}

.btn,
.zero {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    outline: none;
    color: #484848;
    background-color: #f4faff;
    border-radius: 5px;
}

.display,
.answer {
    grid-column: 1 / 5;
    display: flex;
    align-items: center;
}

.display {
    color: #a3a3a3;
    border-bottom: 1px solid #e1e1e1;
    margin-bottom: 15px;
    overflow: hidden;
    text-overflow: clip;
}

.answer {
    font-weight: 500;
    color: #146080;
    font-size: 55px;
    height: 65px;
}

.zero {
    grid-column: 1 / 3;
}

.operator {
    background-color: #d9efff;
    color: #3fa9fc;
}
.history {
    margin-top: 20px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 10px;
}

.history h2 {
    font-weight: bold;
    margin-bottom: 10px;
}

.history-grid {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 10px;
}

.history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    font-family: "Poppins", sans-serif;
    font-size: 16px;
    font-weight: 300;
}

.history-expression {
    color: #333333;
    font-weight: 400;
}

.history-result {
    font-weight: 500;
    color: #146080;
    font-size: 18px;
}
</style>
