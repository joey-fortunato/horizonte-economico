export default function AppLogo() {
    return (
        <>
            {/* Logótipo completo (expandido) */}
            <img
                src="/brand/he-horizontal-negativo.svg"
                alt="Horizonte Económico"
                className="h-7 w-auto group-data-[collapsible=icon]:hidden"
            />
            {/* Símbolo (sidebar recolhida) */}
            <img
                src="/brand/he-simbolo-negativo.svg"
                alt="Horizonte Económico"
                className="hidden size-7 group-data-[collapsible=icon]:block"
            />
        </>
    );
}
